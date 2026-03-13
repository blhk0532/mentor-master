<?php

declare(strict_types=1);

namespace Buildix\Timex\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasUuids;

    public $timestamps = false;

    protected $guarded = [];

    public function getTable()
    {
        return config('timex.tables.category.name', 'timex_categories');
    }
}
