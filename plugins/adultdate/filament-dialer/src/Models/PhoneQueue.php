<?php

declare(strict_types=1);

namespace AdultDate\FilamentDialer\Models;

use Illuminate\Database\Eloquent\Model;

class PhoneQueue extends Model
{
    protected $fillable = ['name', 'address', 'phone', 'status', 'user_id'];

    protected $casts = [
        'status' => 'string',
    ];

    /**
     * Get the user that owns the phone queue.
     */
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }
}
