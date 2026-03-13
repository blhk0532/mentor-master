# Filament Postnummer Plugin

A comprehensive Filament v4 plugin for managing Swedish postal codes (postnummer) with data integration from multiple sources including Hitta, Ratsit, and Merinfo.

## Features

- **Complete Filament Resource**: Full CRUD operations for Swedish postal codes
- **Multi-Source Data Integration**: Track data from Hitta, Ratsit, and Merinfo
- **Advanced Filtering**: Filter by status, active status, and phone availability
- **API Endpoints**: RESTful API for external integrations
- **Export Functionality**: Export data in various formats
- **Queue Management**: Built-in queue management for data processing
- **Detailed Statistics**: Track counts, saved records, and processing progress

## Installation

### 1. Install via Composer

```bash
composer require adultdate/filament-postnummer
```

### 2. Publish Configuration

```bash
php artisan vendor:publish --tag="filament-postnummer-config"
```

### 3. Publish and Run Migrations

```bash
php artisan vendor:publish --tag="filament-postnummer-migrations"
php artisan migrate
```

### 4. Register Plugin

Add the plugin to your Filament panel provider (usually `app/Providers/Filament/AdminPanelProvider.php`):

```php
use Adultdate\FilamentPostnummer\FilamentPostnummerPlugin;

public function panel(Panel $panel): Panel
{
    return $panel
        // ... other panel configuration
        ->plugin(FilamentPostnummerPlugin::make());
}
```

## Configuration

The plugin configuration file is published at `config/filament-postnummer.php`. You can customize:

- Table name
- API routes prefix and middleware
- Resource navigation settings
- External data source configurations

## Database Schema

The plugin creates a `postnummer` table with the following main fields:

- `post_nummer`: Postal code (e.g., "123 45")
- `post_ort`: City name
- `post_lan`: County/region
- Data counts and saved records from Hitta, Ratsit, and Merinfo
- Queue management flags
- Status tracking
- Timestamps

## API Routes

The plugin provides the following API endpoints under `/api/postnummer`:

- `GET /` - List all postnummer with filtering and pagination
- `GET /{postNummer}` - Get specific postnummer
- `PUT /{postNummer}` - Update specific postnummer
- `POST /bulk-update` - Bulk update multiple postnummer

### API Usage Examples

#### Get all postnummer
```bash
curl -X GET "http://your-app.com/api/postnummer?per_page=50&status=active"
```

#### Get specific postnummer
```bash
curl -X GET "http://your-app.com/api/postnummer/123%2045"
```

#### Update postnummer
```bash
curl -X PUT "http://your-app.com/api/postnummer/123%2045" \
  -H "Content-Type: application/json" \
  -d '{"status": "completed", "is_active": true}'
```

#### Bulk update
```bash
curl -X POST "http://your-app.com/api/postnummer/bulk-update" \
  -H "Content-Type: application/json" \
  -d '{
    "post_nummers": ["123 45", "123 46"],
    "data": {"status": "processing"}
  }'
```

## Filament Resource Features

### Table Columns
- Basic info: Postal code, city, county
- Data source groups: Hitta, Ratsit, Merinfo with counts and saved records
- Status indicators and queue flags
- Timestamps

### Filters
- Status filter (pending, running, complete)
- Active status filter
- Phone availability filter

### Actions
- Standard CRUD operations
- Bulk actions for data management
- Export functionality

## Data Sources

The plugin tracks data from three main Swedish directories:

### Hitta.se
- Total person and company counts
- Saved records with phone and house information
- Queue management for data processing

### Ratsit.se
- Person and company data
- Phone and house information
- Processing queues

### Merinfo.se
- Comprehensive person and company data
- Phone number tracking
- Queue and count management

## Queue Management

The plugin includes built-in queue management for processing data from external sources:

- Individual queue flags for each data source
- Bulk queue operations
- Progress tracking
- Status management

## Export Functionality

Export postnummer data in various formats:
- CSV
- Excel (XLSX)
- SQLite database

## Customization

### Custom Fields

You can extend the Postnummer model with custom fields by:

1. Publishing the migration
2. Adding your columns to the migration
3. Updating the model's `$fillable` and `$casts` arrays
4. Modifying the form schema and table columns

### Custom Actions

Add custom bulk actions by extending the `PostnummersTable` class:

```php
// In your custom table class
public static function configure(Table $table): Table
{
    return $table
        ->actions([
            // ... existing actions
            Action::make('customAction')
                ->label('Custom Action')
                ->action(function ($record) {
                    // Your custom logic
                }),
        ]);
}
```

## Troubleshooting

### Migration Issues

If you encounter migration conflicts, you can:

1. Publish the migration: `php artisan vendor:publish --tag="filament-postnummer-migrations"`
2. Modify the published migration file in `database/migrations/`
3. Run the migration manually: `php artisan migrate`

### Route Conflicts

If API routes conflict with existing routes, customize the prefix in the configuration file:

```php
// config/filament-postnummer.php
'api' => [
    'prefix' => 'api/custom-postnummer',
],
```

## Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Add tests if applicable
5. Submit a pull request

## License

This plugin is open-sourced software licensed under the MIT license.

## Support

For support and questions:
- Create an issue in the GitHub repository
- Contact: support@adultdate.com

## Changelog

### v1.0.0
- Initial release
- Complete Filament v4 integration
- API endpoints
- Multi-source data tracking
- Queue management
- Export functionality