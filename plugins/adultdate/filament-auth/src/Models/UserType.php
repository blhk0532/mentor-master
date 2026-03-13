<?php

declare(strict_types=1);

namespace Adultdate\FilamentUser\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserType extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'user_types';

    protected $fillable = [
        'slug',
        'label',
    ];
}
