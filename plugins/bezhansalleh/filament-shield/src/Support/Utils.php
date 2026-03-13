<?php

declare(strict_types=1);

namespace BezhanSalleh\FilamentShield\Support;

use BezhanSalleh\FilamentShield\Facades\FilamentShield;
use Filament\Facades\Filament;
use Filament\Panel;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use RuntimeException;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class Utils
{
    private static ?array $psr4Cache = null;

    public static function getConfig(): ShieldConfig
    {
        return ShieldConfig::init();
    }

    public static function getFilamentAuthGuard(): string
    {
        return Filament::getCurrentOrDefaultPanel()?->getAuthGuard() ?? '';
    }

    public static function isResourcePublished(Panel $panel): bool
    {
        return str(
            string: collect(value: $panel->getResources())
                ->values()
                ->join(',')
        )
            ->contains('\\RoleResource');
    }

    public static function getResourceSlug(): string
    {
        return (string) self::getConfig()->shield_resource->slug;
    }

    public static function getAuthProviderFQCN(): string
    {
        return (string) self::getConfig()->auth_provider_model;
    }

    public static function isAuthProviderConfigured(): bool
    {
        return in_array(\Spatie\Permission\Traits\HasRoles::class, class_uses_recursive(self::getAuthProviderFQCN()));
    }

    public static function isSuperAdminEnabled(): bool
    {
        return (bool) self::getConfig()->super_admin->enabled;
    }

    public static function getSuperAdminName(): string
    {
        return (string) self::getConfig()->super_admin->name;
    }

    public static function isSuperAdminDefinedViaGate(): bool
    {
        return self::isSuperAdminEnabled() && self::getConfig()->super_admin->define_via_gate;
    }

    public static function getSuperAdminGateInterceptionStatus(): string
    {
        return (string) self::getConfig()->super_admin->intercept_gate;
    }

    public static function isPanelUserRoleEnabled(): bool
    {
        return (bool) self::getConfig()->panel_user->enabled;
    }

    public static function getPanelUserRoleName(): string
    {
        return (string) self::getConfig()->panel_user->name;
    }

    public static function createPanelUserRole(): void
    {
        if (self::isPanelUserRoleEnabled()) {
            self::createRole(name: self::getPanelUserRoleName());
        }
    }

    public static function isResourceTabEnabled(): bool
    {
        return (bool) self::getConfig()->shield_resource->tabs->resources;
    }

    public static function isPageTabEnabled(): bool
    {
        return (bool) self::getConfig()->shield_resource->tabs->pages;
    }

    public static function isWidgetTabEnabled(): bool
    {
        return (bool) self::getConfig()->shield_resource->tabs->widgets;
    }

    public static function isCustomPermissionTabEnabled(): bool
    {
        return (bool) self::getConfig()->shield_resource->tabs->custom_permissions;
    }

    public static function getGeneratorOption(): string
    {
        return match (true) {
            self::getConfig()->permissions->generate && self::getConfig()->policies->generate => 'policies_and_permissions',
            self::getConfig()->permissions->generate => 'permissions',
            self::getConfig()->policies->generate => 'policies',
            default => 'none',
        };
    }

    public static function getPolicyPath(): string
    {
        return Str::of(self::getConfig()->policies->path ?? app_path('Policies'))
            ->replace('\\', DIRECTORY_SEPARATOR)
            ->toString();
    }

    public static function getRolePolicyPath(): ?string
    {
        $filesystem = new Filesystem;
        $path = self::getPolicyPath().DIRECTORY_SEPARATOR.'RolePolicy.php';

        return $filesystem->exists($path) ? Str::of(self::resolveNamespaceFromPath($path))->before('.php')->toString() : null;
    }

    public static function isRolePolicyRegistered(): bool
    {
        return filled(self::getRolePolicyPath()) && self::getConfig()->register_role_policy;
    }

    public static function showModelPath(string $resourceFQCN): string
    {
        return config('filament-shield.shield_resource.show_model_path', true)
            ? (new ($resourceFQCN::getModel())())::class
            : '';
    }

    public static function getResourceCluster(): ?string
    {
        return config('filament-shield.shield_resource.cluster');
    }

    public static function getRoleModel(): string
    {
        return app(PermissionRegistrar::class)
            ->getRoleClass();
    }

    public static function getPermissionModel(): string
    {
        return app(PermissionRegistrar::class)
            ->getPermissionClass();
    }

    public static function isTenancyEnabled(): bool
    {
        return (bool) config()->get('permission.teams', false);
    }

    public static function getTenantModelForeignKey(): string
    {
        return config()->get('permission.column_names.team_foreign_key', 'team_id');
    }

    public static function getTenantModel(): ?string
    {
        return self::getConfig()->tenant_model ?? null;
    }

    public static function createRole(?string $name = null, int|string|null $tenantId = null): Role
    {
        $guardName = self::getFilamentAuthGuard();

        if (self::isTenancyEnabled()) {
            return self::getRoleModel()::firstOrCreate(
                [
                    'name' => $name ?? self::getConfig()->super_admin->name,
                    self::getTenantModelForeignKey() => $tenantId,
                    'guard_name' => $guardName,
                ],
            );
        }

        return self::getRoleModel()::firstOrCreate(
            [
                'name' => $name ?? self::getSuperAdminName(),
                'guard_name' => $guardName,
            ],
        );
    }

    public static function createPermission(string $name): string
    {
        return self::getPermissionModel()::firstOrCreate(
            ['name' => $name, 'guard_name' => self::getFilamentAuthGuard()],
        )->name;
    }

    public static function giveSuperAdminPermission(string|array|Collection $permissions): void
    {
        if (! self::isSuperAdminDefinedViaGate() && self::isSuperAdminEnabled()) {
            $superAdmin = self::createRole();

            $superAdmin->givePermissionTo($permissions);

            app(PermissionRegistrar::class)->forgetCachedPermissions();
        }
    }

    public static function generateForResource(string $resourceKey): void
    {
        $permissions = collect(FilamentShield::getResourcePermissions($resourceKey))
            ->map(self::createPermission(...))
            ->toArray();

        self::giveSuperAdminPermission($permissions);
    }

    public static function generateForPageOrWidget(string $name): void
    {
        self::giveSuperAdminPermission(self::createPermission($name));
    }

    public static function generateForExtraPermissions(): void
    {
        $customPermissions = collect(FilamentShield::getCustomPermissions())->keys();

        if ($customPermissions->isNotEmpty()) {
            $permissions = $customPermissions
                ->map(self::createPermission(...))
                ->toArray();

            self::giveSuperAdminPermission($permissions);
        }
    }

    public static function resolveNamespaceFromPath(string $configuredPath): string
    {
        // Cache PSR-4 mappings to avoid repeated file I/O
        if (self::$psr4Cache === null) {
            $composer = json_decode(file_get_contents(base_path('composer.json')), true);
            self::$psr4Cache = $composer['autoload']['psr-4'] ?? [];
        }

        // Normalize path separators once
        $configuredPath = str_replace(['\\', '/'], DIRECTORY_SEPARATOR, $configuredPath);

        // Convert relative path to absolute
        if (! self::isAbsolutePath($configuredPath)) {
            $configuredPath = base_path($configuredPath);
        }

        // Normalize and prepare for comparison
        $checkPath = mb_rtrim($configuredPath, DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR;
        $checkPathLower = mb_strtolower($checkPath);

        foreach (self::$psr4Cache as $namespace => $base) {
            $basePath = mb_rtrim(base_path(str_replace(['\\', '/'], DIRECTORY_SEPARATOR, $base)), DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR;
            $basePathLower = mb_strtolower($basePath);

            // Fast path: exact match
            if ($checkPathLower === $basePathLower) {
                return mb_rtrim((string) $namespace, '\\');
            }

            // Check if configured path is within this PSR-4 base
            if (str_starts_with($checkPathLower, $basePathLower)) {
                $relative = mb_substr($checkPath, mb_strlen($basePath));
                $relative = mb_rtrim($relative, DIRECTORY_SEPARATOR);

                $ns = mb_rtrim((string) $namespace, '\\');
                if ($relative !== '') {
                    $ns .= '\\'.str_replace(DIRECTORY_SEPARATOR, '\\', $relative);
                }

                return $ns;
            }
        }

        throw new RuntimeException('Configured path does not match any PSR-4 mapping: '.$configuredPath);
    }

    /**
     * Convert a permission key to a localization key.
     *
     * Removes the configured separator and converts to snake_case.
     */
    public static function toLocalizationKey(string $key): string
    {
        $separator = self::getConfig()->permissions->separator;

        return Str::of($key)
            ->replace($separator, '_')
            ->snake()
            ->replace('__', '_')
            ->toString();
    }

    private static function isAbsolutePath(string $path): bool
    {
        // windows os
        if (preg_match('/^[a-zA-Z]:[\\\\\\/]/', $path)) {
            return true;
        }

        return str_starts_with($path, DIRECTORY_SEPARATOR);
    }
}
