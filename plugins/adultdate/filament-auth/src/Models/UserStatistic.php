<?php

declare(strict_types=1);

namespace Adultdate\FilamentUser\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserStatistic extends Model
{
    use HasFactory;

    public $timestamps = true;

    protected $table = 'user_stats';

    protected $fillable = [
        'user_id',
        'key',
        'value',
    ];

    protected $casts = [
        'value' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(\Adultdate\FilamentAuth\Models\User::class);
    }
}
