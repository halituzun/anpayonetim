<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ManuelPersonel extends Model
{
    protected $table = 'manuel_personels';

    protected $fillable = [
        'personel_kodu',
        'personel_adsoyad',
        'unvan_kod',
        'yetkili_depolar',
        'aktif',
        'ana_tabloda_var'
    ];

    protected $casts = [
        'yetkili_depolar' => 'array',
        'aktif' => 'boolean',
        'ana_tabloda_var' => 'boolean'
    ];

    // Varsayılan değerler
    protected $attributes = [
        'aktif' => true,
        'ana_tabloda_var' => false
    ];
}