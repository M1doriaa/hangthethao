<?php

require_once 'vendor/autoload.php';

use Maatwebsite\Excel\Facades\Excel;

// Đường dẫn file Excel
$excelFile = 'TinhHuyenXa2021 (1).xlsx';

if (!file_exists($excelFile)) {
    echo "File Excel không tồn tại: $excelFile\n";
    exit(1);
}

echo "Đang đọc file Excel: $excelFile\n";

try {
    // Đọc file Excel
    $data = Excel::toArray([], $excelFile);
    
    if (empty($data) || empty($data[0])) {
        echo "File Excel rỗng hoặc không có dữ liệu\n";
        exit(1);
    }
    
    $rows = $data[0]; // Lấy sheet đầu tiên
    $header = array_shift($rows); // Loại bỏ header row
    
    echo "Tìm thấy " . count($rows) . " dòng dữ liệu\n";
    echo "Header: " . implode(' | ', $header) . "\n";
    
    // Hiển thị 5 dòng đầu tiên để kiểm tra cấu trúc
    echo "\n5 dòng đầu tiên:\n";
    for ($i = 0; $i < min(5, count($rows)); $i++) {
        echo "Row " . ($i + 1) . ": " . implode(' | ', $rows[$i]) . "\n";
    }
    
    // Xuất dữ liệu thành file PHP array để import vào seeder
    $output = "<?php\n\nreturn [\n";
    $provinces = [];
    $districts = [];
    $wards = [];
    
    foreach ($rows as $row) {
        if (count($row) >= 3) {
            // Giả sử cấu trúc: [province_code, province_name, district_code, district_name, ward_code, ward_name, ...]
            // Cần xem cấu trúc thực tế từ output trên
            
            // Tạm thời tạo dữ liệu mẫu
            $provinceCode = isset($row[0]) ? trim($row[0]) : '';
            $provinceName = isset($row[1]) ? trim($row[1]) : '';
            $districtCode = isset($row[2]) ? trim($row[2]) : '';
            $districtName = isset($row[3]) ? trim($row[3]) : '';
            $wardCode = isset($row[4]) ? trim($row[4]) : '';
            $wardName = isset($row[5]) ? trim($row[5]) : '';
            
            if ($provinceCode && $provinceName && !isset($provinces[$provinceCode])) {
                $provinces[$provinceCode] = [
                    'code' => $provinceCode,
                    'name' => $provinceName,
                    'full_name' => $provinceName,
                    'code_name' => strtolower(str_replace(' ', '-', $provinceName))
                ];
            }
            
            if ($districtCode && $districtName && $provinceCode && !isset($districts[$districtCode])) {
                $districts[$districtCode] = [
                    'code' => $districtCode,
                    'name' => $districtName,
                    'full_name' => $districtName,
                    'code_name' => strtolower(str_replace(' ', '-', $districtName)),
                    'province_code' => $provinceCode
                ];
            }
            
            if ($wardCode && $wardName && $districtCode && !isset($wards[$wardCode])) {
                $wards[$wardCode] = [
                    'code' => $wardCode,
                    'name' => $wardName,
                    'full_name' => $wardName,
                    'code_name' => strtolower(str_replace(' ', '-', $wardName)),
                    'district_code' => $districtCode
                ];
            }
        }
    }
    
    $output .= "    'provinces' => " . var_export(array_values($provinces), true) . ",\n";
    $output .= "    'districts' => " . var_export(array_values($districts), true) . ",\n";
    $output .= "    'wards' => " . var_export(array_values($wards), true) . ",\n";
    $output .= "];\n";
    
    file_put_contents('address_data.php', $output);
    
    echo "\nĐã xuất dữ liệu vào file address_data.php\n";
    echo "Tổng số tỉnh/thành phố: " . count($provinces) . "\n";
    echo "Tổng số quận/huyện: " . count($districts) . "\n";
    echo "Tổng số phường/xã: " . count($wards) . "\n";
    
} catch (Exception $e) {
    echo "Lỗi khi đọc file Excel: " . $e->getMessage() . "\n";
    exit(1);
}
