<?php

declare(strict_types=1);

namespace Adultdate\FilamentUser\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserSetting extends Model
{
    use HasFactory;

    public $timestamps = true;

    protected $table = 'user_settings';

    protected $fillable = [
        'user_id',
        'key',
        'value',
    ];

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }
}
