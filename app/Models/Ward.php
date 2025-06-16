<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ward extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'name_en',
        'full_name',
        'full_name_en',
        'code_name',
        'district_code',
        'administrative_unit_id',
    ];

    protected $primaryKey = 'id';
    public $incrementing = true;

    /**
     * Quan hệ với District
     */
    public function district()
    {
        return $this->belongsTo(District::class, 'district_code', 'code');
    }

    /**
     * Quan hệ với Province thông qua District
     */
    public function province()
    {
        return $this->hasOneThrough(Province::class, District::class, 'code', 'code', 'district_code', 'province_code');
    }
}
