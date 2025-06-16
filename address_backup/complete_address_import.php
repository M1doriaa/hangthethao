<?php

require_once 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Database\Capsule\Manager as Capsule;

// Khởi tạo kết nối database
$config = require 'config/database.php';
$db = new Capsule;
$db->addConnection([
    'driver' => 'sqlite',
    'database' => database_path('database.sqlite'),
    'prefix' => '',
]);
$db->setAsGlobal();
$db->bootEloquent();

function database_path($path = '') {
    return __DIR__ . '/database/' . $path;
}

echo "Bắt đầu import dữ liệu địa chỉ hoàn chỉnh...\n";

try {
    // Đọc file Excel
    $inputFileName = 'TinhHuyenXa2021 (1).xlsx';
    
    if (!file_exists($inputFileName)) {
        die("Không tìm thấy file Excel: $inputFileName\n");
    }
    
    echo "Đang đọc file Excel: $inputFileName\n";
    $spreadsheet = IOFactory::load($inputFileName);
    $worksheet = $spreadsheet->getActiveSheet();
    $rows = $worksheet->toArray();
    
    // Bỏ qua dòng tiêu đề
    array_shift($rows);
    
    $provinces = [];
    $districts = [];
    $wards = [];
    
    echo "Đang xử lý dữ liệu...\n";
    
    foreach ($rows as $row) {
        // Giả sử cấu trúc: Mã tỉnh, Tên tỉnh, Mã quận, Tên quận, Mã phường, Tên phường
        if (count($row) < 6) continue;
        
        $provinceCode = trim($row[0]);
        $provinceName = trim($row[1]);
        $districtCode = trim($row[2]);
        $districtName = trim($row[3]);
        $wardCode = trim($row[4]);
        $wardName = trim($row[5]);
        
        if (empty($provinceCode) || empty($provinceName)) continue;
        
        // Lưu tỉnh/thành phố
        if (!isset($provinces[$provinceCode])) {
            $provinces[$provinceCode] = [
                'code' => $provinceCode,
                'name' => $provinceName
            ];
        }
        
        // Lưu quận/huyện
        if (!empty($districtCode) && !empty($districtName)) {
            $districtKey = $provinceCode . '_' . $districtCode;
            if (!isset($districts[$districtKey])) {
                $districts[$districtKey] = [
                    'code' => $districtCode,
                    'name' => $districtName,
                    'province_code' => $provinceCode
                ];
            }
            
            // Lưu phường/xã
            if (!empty($wardCode) && !empty($wardName)) {
                $wardKey = $districtCode . '_' . $wardCode;
                if (!isset($wards[$wardKey])) {
                    $wards[$wardKey] = [
                        'code' => $wardCode,
                        'name' => $wardName,
                        'district_code' => $districtCode
                    ];
                }
            }
        }
    }
    
    echo "Tìm thấy:\n";
    echo "- " . count($provinces) . " tỉnh/thành phố\n";
    echo "- " . count($districts) . " quận/huyện\n";
    echo "- " . count($wards) . " phường/xã\n";
    
    // Xóa dữ liệu cũ (nếu cần)
    echo "Đang xóa dữ liệu cũ...\n";
    Capsule::table('wards')->delete();
    Capsule::table('districts')->delete();
    Capsule::table('provinces')->delete();
    
    // Import tỉnh/thành phố
    echo "Đang import tỉnh/thành phố...\n";
    $provinceData = [];
    foreach ($provinces as $province) {
        $provinceData[] = [
            'code' => $province['code'],
            'name' => $province['name'],
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ];
    }
    
    // Chia nhỏ để insert
    $chunks = array_chunk($provinceData, 50);
    foreach ($chunks as $chunk) {
        Capsule::table('provinces')->insert($chunk);
    }
    
    // Import quận/huyện
    echo "Đang import quận/huyện...\n";
    $districtData = [];
    foreach ($districts as $district) {
        // Lấy ID của tỉnh
        $provinceId = Capsule::table('provinces')
            ->where('code', $district['province_code'])
            ->value('id');
            
        if ($provinceId) {
            $districtData[] = [
                'code' => $district['code'],
                'name' => $district['name'],
                'province_id' => $provinceId,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ];
        }
    }
    
    $chunks = array_chunk($districtData, 50);
    foreach ($chunks as $chunk) {
        Capsule::table('districts')->insert($chunk);
    }
    
    // Import phường/xã
    echo "Đang import phường/xã...\n";
    $wardData = [];
    foreach ($wards as $ward) {
        // Lấy ID của quận/huyện
        $districtId = Capsule::table('districts')
            ->where('code', $ward['district_code'])
            ->value('id');
            
        if ($districtId) {
            $wardData[] = [
                'code' => $ward['code'],
                'name' => $ward['name'],
                'district_id' => $districtId,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ];
        }
    }
    
    $chunks = array_chunk($wardData, 100);
    foreach ($chunks as $chunk) {
        Capsule::table('wards')->insert($chunk);
    }
    
    echo "Import hoàn tất!\n";
    
    // Thống kê kết quả
    $finalProvinces = Capsule::table('provinces')->count();
    $finalDistricts = Capsule::table('districts')->count();
    $finalWards = Capsule::table('wards')->count();
    
    echo "\nThống kê cuối cùng:\n";
    echo "- Tỉnh/thành phố: $finalProvinces\n";
    echo "- Quận/huyện: $finalDistricts\n";
    echo "- Phường/xã: $finalWards\n";
    
} catch (Exception $e) {
    echo "Lỗi: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . "\n";
    echo "Line: " . $e->getLine() . "\n";
}
