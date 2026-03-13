<?php

declare(strict_types=1);

namespace Adultdate\FilamentShop\Filament\Clusters\Products;

use BackedEnum;
use Filament\Clusters\Cluster;
use UnitEnum;

class ProductsCluster extends Cluster
{
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-squares-2x2';

    protected static string|UnitEnum|null $navigationGroup = 'Produkter';

    protected static ?int $navigationSort = 0;

    protected static ?string $slug = 'shop/products';
}
