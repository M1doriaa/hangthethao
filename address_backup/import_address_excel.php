<?php

require_once 'vendor/autoload.php';

use App\Models\Province;
use App\Models\District;
use App\Models\Ward;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "Bắt đầu import dữ liệu địa chỉ từ Excel...\n";

try {
    // Đọc file Excel
    $filePath = 'TinhHuyenXa2021 (1).xlsx';
    
    if (!file_exists($filePath)) {
        echo "Không tìm thấy file Excel: {$filePath}\n";
        exit(1);
    }

    // Xóa dữ liệu cũ
    DB::statement('SET FOREIGN_KEY_CHECKS=0;');
    Ward::truncate();
    District::truncate();
    Province::truncate();
    DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    
    echo "Đã xóa dữ liệu cũ\n";

    // Đọc dữ liệu từ Excel
    $data = Excel::toArray([], $filePath);
    
    if (empty($data) || empty($data[0])) {
        echo "File Excel trống hoặc không đúng format\n";
        exit(1);
    }

    $rows = $data[0]; // Lấy sheet đầu tiên
    $header = array_shift($rows); // Bỏ dòng header
    
    echo "Đã đọc " . count($rows) . " dòng dữ liệu\n";
    
    $provinces = [];
    $districts = [];
    $wards = [];
    
    foreach ($rows as $index => $row) {
        if (count($row) < 6) continue; // Bỏ qua dòng không đủ dữ liệu
        
        // Giả sử format: [province_code, province_name, district_code, district_name, ward_code, ward_name]
        $provinceCode = trim($row[0]);
        $provinceName = trim($row[1]);
        $districtCode = trim($row[2]);
        $districtName = trim($row[3]);
        $wardCode = trim($row[4]);
        $wardName = trim($row[5]);
        
        if (!$provinceCode || !$provinceName || !$districtCode || !$districtName || !$wardCode || !$wardName) {
            continue;
        }
        
        // Tạo province nếu chưa có
        if (!isset($provinces[$provinceCode])) {
            $provinces[$provinceCode] = [
                'code' => $provinceCode,
                'name' => $provinceName,
                'full_name' => $provinceName,
                'code_name' => str_slug($provinceName),
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        
        // Tạo district nếu chưa có
        $districtKey = $provinceCode . '_' . $districtCode;
        if (!isset($districts[$districtKey])) {
            $districts[$districtKey] = [
                'code' => $districtCode,
                'name' => $districtName,
                'full_name' => $districtName,
                'code_name' => str_slug($districtName),
                'province_code' => $provinceCode,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        
        // Tạo ward
        $wardKey = $districtCode . '_' . $wardCode;
        if (!isset($wards[$wardKey])) {
            $wards[$wardKey] = [
                'code' => $wardCode,
                'name' => $wardName,
                'full_name' => $wardName,
                'code_name' => str_slug($wardName),
                'district_code' => $districtCode,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        
        if (($index + 1) % 1000 == 0) {
            echo "Đã xử lý " . ($index + 1) . " dòng\n";
        }
    }
    
    echo "Bắt đầu lưu vào database...\n";
    
    // Insert provinces
    foreach (array_chunk($provinces, 100) as $chunk) {
        Province::insert($chunk);
    }
    echo "Đã insert " . count($provinces) . " tỉnh/thành phố\n";
    
    // Insert districts
    foreach (array_chunk($districts, 100) as $chunk) {
        District::insert($chunk);
    }
    echo "Đã insert " . count($districts) . " quận/huyện\n";
    
    // Insert wards
    foreach (array_chunk($wards, 100) as $chunk) {
        Ward::insert($chunk);
    }
    echo "Đã insert " . count($wards) . " phường/xã\n";
    
    echo "Import hoàn tất!\n";
    echo "Tổng cộng:\n";
    echo "- Tỉnh/Thành phố: " . count($provinces) . "\n";
    echo "- Quận/Huyện: " . count($districts) . "\n";
    echo "- Phường/Xã: " . count($wards) . "\n";
    
} catch (Exception $e) {
    echo "Lỗi: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
}

// Helper function nếu không có sẵn
if (!function_exists('str_slug')) {
    function str_slug($string) {
        return \Illuminate\Support\Str::slug($string);
    }
}
