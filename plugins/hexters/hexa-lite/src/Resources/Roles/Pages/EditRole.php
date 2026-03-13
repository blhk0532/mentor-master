<?php

declare(strict_types=1);

namespace Hexters\HexaLite\Resources\Roles\Pages;

use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Hexters\HexaLite\Resources\Roles\RoleResource;

class EditRole extends EditRecord
{
    protected static string $resource = RoleResource::class;

    public static function canAccess(array $parameters = []): bool
    {
        return hexa()->can('role.update');
    }

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make()
                ->visible(fn () => hexa()->can('role.delete')),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $data['gates'] = $data['access'];

        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $data['access'] = $data['gates'] ?? [];

        return $data;
    }
}
