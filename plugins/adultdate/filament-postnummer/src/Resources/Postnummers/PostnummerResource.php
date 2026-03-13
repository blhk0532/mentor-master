<?php

declare(strict_types=1);

namespace Adultdate\FilamentPostnummer\Resources\Postnummers;

use Adultdate\FilamentPostnummer\Resources\Postnummers\Pages\CreatePostnummer;
use Adultdate\FilamentPostnummer\Resources\Postnummers\Pages\EditPostnummer;
use Adultdate\FilamentPostnummer\Resources\Postnummers\Pages\ListPostnummers;
use Adultdate\FilamentPostnummer\Resources\Postnummers\Pages\ViewPostnummer;
use Adultdate\FilamentPostnummer\Resources\Postnummers\Schemas\PostnummerForm;
use Adultdate\FilamentPostnummer\Resources\Postnummers\Schemas\PostnummerInfolist;
use Adultdate\FilamentPostnummer\Resources\Postnummers\Tables\PostnummersTable;
use App\Models\Postnummer;
use BackedEnum;
use BezhanSalleh\PluginEssentials\Concerns\Resource as Essentials;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class PostnummerResource extends Resource
{
    use Essentials\BelongsToParent;
    use Essentials\BelongsToTenant;
    use Essentials\HasGlobalSearch;
    use Essentials\HasLabels;
    use Essentials\HasNavigation;

    protected static ?string $model = Postnummer::class;

    protected static ?int $navigationSort = 2;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $navigationLabel = 'Post Nummer';

    protected static string|UnitEnum|null $navigationGroup = 'Database SWE';

    public static function form(Schema $schema): Schema
    {
        return PostnummerForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return PostnummerInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PostnummersTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    /**
     * @return array<string>
     */
    public static function getGloballySearchableAttributes(): array
    {
        // Make post numbers and place names globally searchable
        return [
            'post_nummer',
            'post_ort',
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return self::getModel()::count();
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPostnummers::route('/'),
            'create' => CreatePostnummer::route('/create'),
            'view' => ViewPostnummer::route('/{record}'),
            'edit' => EditPostnummer::route('/{record}/edit'),
        ];
    }
}
