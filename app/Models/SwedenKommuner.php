<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SwedenKommuner extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'sweden_kommuner';

    /** @var array<int, string> */
    protected $fillable = [
        'kommun', 'lan', 'personer', 'foretag', 'latitude', 'longitude',
    ];
}
