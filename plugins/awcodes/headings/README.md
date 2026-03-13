# Headings Plugin

A Filament plugin that adds a Heading component for forms.

## Installation

Install via Composer:

```bash
composer require awcodes/headings
```

## Usage

Use the Heading component in your forms:

```php
use Awcodes\Headings\Heading;

Heading::make(2)
    ->content('Product Information')
    ->color(Color::Lime),
```