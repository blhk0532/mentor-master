<?php

declare(strict_types=1);

namespace Adultdate\FilamentPostnummer\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Postnummer extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'post_nummer',
        'post_ort',
        'post_lan',
        'hitta_personer_total',
        'hitta_foretag_total',
        'hitta_personer_saved',
        'hitta_personer_house_saved',
        'hitta_foretag_saved',
        'hitta_personer_phone_saved',
        'hitta_foretag_phone_saved',
        'hitta_personer_queue',
        'hitta_foretag_queue',
        'ratsit_personer_total',
        'ratsit_foretag_total',
        'ratsit_personer_saved',
        'ratsit_personer_house_saved',
        'ratsit_foretag_saved',
        'ratsit_personer_phone_saved',
        'ratsit_foretag_phone_saved',
        'ratsit_personer_queue',
        'ratsit_foretag_queue',
        'merinfo_personer_total',
        'merinfo_foretag_total',
        'merinfo_personer_phone_total',
        'merinfo_foretag_phone_total',
        'merinfo_personer_phone_saved',
        'merinfo_foretag_phone_saved',
        'merinfo_personer_saved',
        'merinfo_personer_house_saved',
        'merinfo_foretag_saved',
        'merinfo_personer_queue',
        'merinfo_personer_count',
        'merinfo_foretag_queue',
        'hitta_postort_total_pages',
        'hitta_postort_processed_pages',
        'hitta_postort_last_page',
        'is_active',
        'status',
    ];

    protected $casts = [
        'id' => 'string',
        'post_nummer' => 'string',
        'post_ort' => 'string',
        'post_lan' => 'string',
        'hitta_personer_total' => 'integer',
        'hitta_foretag_total' => 'integer',
        'hitta_personer_saved' => 'integer',
        'hitta_personer_house_saved' => 'integer',
        'hitta_foretag_saved' => 'integer',
        'hitta_personer_phone_saved' => 'integer',
        'hitta_foretag_phone_saved' => 'integer',
        'hitta_personer_queue' => 'boolean',
        'hitta_foretag_queue' => 'boolean',
        'ratsit_personer_saved' => 'integer',
        'ratsit_personer_house_saved' => 'integer',
        'ratsit_foretag_saved' => 'integer',
        'ratsit_personer_phone_saved' => 'integer',
        'ratsit_foretag_phone_saved' => 'integer',
        'ratsit_personer_total' => 'integer',
        'ratsit_foretag_total' => 'integer',
        'ratsit_personer_queue' => 'boolean',
        'ratsit_foretag_queue' => 'boolean',
        'merinfo_personer_total' => 'integer',
        'merinfo_foretag_total' => 'integer',
        'merinfo_personer_saved' => 'integer',
        'merinfo_personer_house_saved' => 'integer',
        'merinfo_foretag_saved' => 'integer',
        'merinfo_personer_phone_saved' => 'integer',
        'merinfo_foretag_phone_saved' => 'integer',
        'merinfo_personer_phone_total' => 'integer',
        'merinfo_foretag_phone_total' => 'integer',
        'merinfo_personer_queue' => 'boolean',
        'merinfo_personer_count' => 'boolean',
        'merinfo_foretag_queue' => 'boolean',
        'hitta_postort_total_pages' => 'integer',
        'hitta_postort_processed_pages' => 'integer',
        'hitta_postort_last_page' => 'integer',
        'is_active' => 'boolean',
        'status' => 'string',
    ];

    public function getTable(): string
    {
        return 'postnummer';
    }

    public function getIncrementing(): bool
    {
        return false;
    }

    public function getKeyType(): string
    {
        return 'string';
    }

    public function getKey(): string
    {
        return $this->getAttribute('id');
    }
}
