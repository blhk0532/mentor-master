<?php

declare(strict_types=1);

namespace MWGuerra\FileManager\Filament\Resources\FileSystemItemResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use MWGuerra\FileManager\Filament\Resources\FileSystemItemResource;

class CreateFileSystemItem extends CreateRecord
{
    protected static string $resource = FileSystemItemResource::class;
}
