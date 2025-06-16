<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Province;
use App\Models\District;
use App\Models\Ward;
use Illuminate\Http\Request;

class AddressController extends Controller
{
    /**
     * Lấy danh sách tất cả tỉnh/thành phố
     */
    public function getProvinces()
    {
        $provinces = Province::orderBy('name')->get(['id', 'code', 'name', 'full_name']);
        
        return response()->json([
            'success' => true,
            'data' => $provinces
        ]);
    }

    /**
     * Lấy danh sách quận/huyện theo tỉnh/thành phố
     */
    public function getDistricts($provinceCode)
    {
        $districts = District::where('province_code', $provinceCode)
            ->orderBy('name')
            ->get(['id', 'code', 'name', 'full_name', 'province_code']);
        
        return response()->json([
            'success' => true,
            'data' => $districts
        ]);
    }

    /**
     * Lấy danh sách phường/xã theo quận/huyện
     */
    public function getWards($districtCode)
    {
        $wards = Ward::where('district_code', $districtCode)
            ->orderBy('name')
            ->get(['id', 'code', 'name', 'full_name', 'district_code']);
        
        return response()->json([
            'success' => true,
            'data' => $wards
        ]);
    }

    /**
     * Tìm kiếm địa chỉ
     */
    public function search(Request $request)
    {
        $query = $request->get('q');
        if (!$query) {
            return response()->json([
                'success' => false,
                'message' => 'Từ khóa tìm kiếm không được để trống'
            ]);
        }

        $provinces = Province::where('name', 'LIKE', "%{$query}%")
            ->orWhere('full_name', 'LIKE', "%{$query}%")
            ->limit(10)
            ->get(['id', 'code', 'name', 'full_name']);

        $districts = District::where('name', 'LIKE', "%{$query}%")
            ->orWhere('full_name', 'LIKE', "%{$query}%")
            ->with('province:id,code,name')
            ->limit(10)
            ->get(['id', 'code', 'name', 'full_name', 'province_code']);

        $wards = Ward::where('name', 'LIKE', "%{$query}%")
            ->orWhere('full_name', 'LIKE', "%{$query}%")
            ->with(['district:id,code,name,province_code', 'district.province:id,code,name'])
            ->limit(10)
            ->get(['id', 'code', 'name', 'full_name', 'district_code']);

        return response()->json([
            'success' => true,
            'data' => [
                'provinces' => $provinces,
                'districts' => $districts,
                'wards' => $wards
            ]
        ]);
    }
}
