<?php

declare(strict_types=1);

namespace BezhanSalleh\FilamentExceptions\Resources;

use BezhanSalleh\FilamentExceptions\FilamentExceptions;
use BezhanSalleh\FilamentExceptions\FilamentExceptionsPlugin;
use BezhanSalleh\FilamentExceptions\Resources\ExceptionResource\Pages\ListExceptions;
use BezhanSalleh\FilamentExceptions\Resources\ExceptionResource\Pages\ViewException;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ViewAction;
use Filament\Panel;
use Filament\Resources\Resource;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Contracts\Database\Eloquent\Builder;
use LogicException;

class ExceptionResource extends Resource
{
    public static function getCluster(): ?string
    {
        return FilamentExceptions::getCluster();
    }

    public static function getPlugin(): ?FilamentExceptionsPlugin
    {
        try {
            return FilamentExceptionsPlugin::get();
        } catch (LogicException $e) {
            return null;
        }
    }

    public static function getModel(): string
    {
        return FilamentExceptions::getModel();
    }

    public static function getModelLabel(): string
    {
        return self::getPlugin()?->getModelLabel() ?? 'Exception';
    }

    public static function getPluralModelLabel(): string
    {
        return self::getPlugin()?->getPluralModelLabel() ?? 'Exceptions';
    }

    public static function getActiveNavigationIcon(): ?string
    {
        return self::getPlugin()?->getActiveNavigationIcon() ?? 'heroicon-o-exclamation-triangle';
    }

    public static function getNavigationGroup(): ?string
    {
        return self::getPlugin()?->getNavigationGroup() ?? self::getTitleCasePluralModelLabel();
    }

    public static function getNavigationLabel(): string
    {
        return self::getPlugin()?->getNavigationLabel() ?? 'Exceptions';
    }

    public static function getNavigationIcon(): string
    {
        return self::getPlugin()?->getNavigationIcon() ?? 'heroicon-o-exclamation-triangle';
    }

    public static function getSlug(?Panel $panel = null): string
    {
        return self::getPlugin()?->getSlug() ?? parent::getSlug($panel);
    }

    public static function getNavigationBadge(): ?string
    {
        return self::getPlugin()?->shouldEnableNavigationBadge()
            ? (string) self::getEloquentQuery()->count()
            : null;
    }

    public static function shouldRegisterNavigation(): bool
    {
        return filled(FilamentExceptions::getCluster()) || self::getPlugin()?->shouldRegisterNavigation() ?? true;
    }

    public static function getNavigationSort(): ?int
    {
        return self::getPlugin()?->getNavigationSort();
    }

    public static function isScopedToTenant(): bool
    {
        return self::getPlugin()?->isScopedToTenant() ?? false;
    }

    public static function getTenantRelationshipName(): string
    {
        return self::getPlugin()?->getTenantRelationshipName() ?? parent::getTenantRelationshipName();
    }

    public static function getTenantOwnershipRelationshipName(): string
    {
        return self::getPlugin()->getTenantOwnershipRelationshipName() ?? parent::getTenantOwnershipRelationshipName();
    }

    public static function canGloballySearch(): bool
    {
        return self::getPlugin()->canGloballySearch() && parent::canGloballySearch();
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn (Builder $query) => $query->select('id', 'path', 'method', 'type', 'code', 'ip', 'created_at'))
            ->columns([
                TextColumn::make('method')
                    ->label(fn (): string => __('filament-exceptions::filament-exceptions.columns.method'))
                    ->badge()
                    ->colors([
                        'gray',
                        'success' => fn ($state): bool => $state === 'GET',
                        'primary' => fn ($state): bool => $state === 'POST',
                        'warning' => fn ($state): bool => in_array($state, ['PUT', 'PATCH'], true),
                        'danger' => fn ($state): bool => $state === 'DELETE',
                        'gray' => fn ($state): bool => $state === 'OPTIONS',

                    ])
                    ->searchable()
                    ->sortable(),
                TextColumn::make('path')
                    ->label(fn (): string => __('filament-exceptions::filament-exceptions.columns.path'))
                    ->searchable(),
                TextColumn::make('type')
                    ->label(fn (): string => __('filament-exceptions::filament-exceptions.columns.type'))
                    ->sortable()
                    ->searchable(),
                TextColumn::make('code')
                    ->label(fn (): string => __('filament-exceptions::filament-exceptions.columns.code'))
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false),
                TextColumn::make('ip')
                    ->label(fn (): string => __('filament-exceptions::filament-exceptions.columns.ip'))
                    ->badge()
                    ->extraAttributes(['class' => 'font-mono'])
                    ->sortable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: false),
                TextColumn::make('created_at')
                    ->label(fn (): string => __('filament-exceptions::filament-exceptions.columns.occurred_at'))
                    ->sortable()
                    ->searchable()
                    ->dateTime()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make()
                    ->color('primary'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => ListExceptions::route('/'),
            'view' => ViewException::route('/{record}'),
        ];
    }
}
