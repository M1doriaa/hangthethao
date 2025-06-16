<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class District extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'name_en',
        'full_name',
        'full_name_en',
        'code_name',
        'province_code',
        'administrative_unit_id',
    ];

    protected $primaryKey = 'id';
    public $incrementing = true;

    /**
     * Quan hệ với Province
     */
    public function province()
    {
        return $this->belongsTo(Province::class, 'province_code', 'code');
    }

    /**
     * Quan hệ với Ward
     */
    public function wards()
    {
        return $this->hasMany(Ward::class, 'district_code', 'code');
    }
}
