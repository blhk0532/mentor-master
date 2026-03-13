# Installation Guide

## Quick Start

### 1. Install via Composer

```bash
composer require anish/text-input-entry
```

### 2. Build Assets

Navigate to the package directory and build assets:

```bash
cd packages/TextInputEntry
npm install
npm run build
```

Or if installed via Composer in vendor:

```bash
cd vendor/anish/text-input-entry
npm install
npm run build
```

### 3. Use the Trait

Add the trait to your Filament resource's view page:

```php
<?php

namespace App\Filament\Resources\Users\Pages;

use Filament\Resources\Pages\ViewRecord;
use Anish\TextInputEntry\Traits\TextInputEntryTrait;

class ViewUser extends ViewRecord
{
    use TextInputEntryTrait;

    protected static string $resource = UserResource::class;
}
```

### 4. Use the Component

In your infolist schema:

```php
<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Schemas\Schema;
use Anish\TextInputEntry\Infolists\Components\TextInputEntry;

class UserInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInputEntry::make('name')
                    ->editable(true)
                    ->border(true),
            ]);
    }
}
```

## That's it!

The package will automatically register itself when installed via Composer. The service provider is auto-discovered through the `extra.laravel.providers` configuration in `composer.json`.

