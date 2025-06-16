<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Province extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'name_en',
        'full_name',
        'full_name_en',
        'code_name',
        'administrative_unit_id',
        'administrative_region_id',
    ];

    protected $primaryKey = 'id';
    public $incrementing = true;

    /**
     * Quan hệ với District
     */
    public function districts()
    {
        return $this->hasMany(District::class, 'province_code', 'code');
    }

    /**
     * Lấy tất cả Ward thuộc Province này thông qua District
     */
    public function wards()
    {
        return $this->hasManyThrough(Ward::class, District::class, 'province_code', 'district_code', 'code', 'code');
    }
}
