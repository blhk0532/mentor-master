# Filament User Plugin

Extends the `User` model with `role`, `type`, `phone`, `team` and adds `user_types`, `user_settings`, and `user_stats` tables plus Filament resources for managing types and users.

Installation (local development):

1. Add this repository as a path repository or copy the plugin into `plugins/filament-user`.
2. Require it in composer (if published) or autoload via your app's composer.json.
3. Run migrations for the plugin:

```bash
php artisan migrate --path=plugins/filament-user/database/migrations
```

Resources provided:
- `Adultdate\FilamentUser\Resources\UserResource` — management UI for `App\Models\User` including extra fields.
- `Adultdate\FilamentUser\Resources\UserTypeResource` — CRUD for available user types.

Testing:

Run the plugin test(s):

```bash
php artisan test --testsuite=Feature --filter MigrationsTest
```

Notes:
- This plugin follows Filament v4 conventions for resources and pages.
- You may need to run `composer dump-autoload` if adding plugin as a local package.
