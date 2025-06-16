<?php

// Cập nhật dữ liệu địa chỉ Việt Nam
require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Province;
use App\Models\District;
use App\Models\Ward;

echo "Bắt đầu cập nhật dữ liệu địa chỉ Việt Nam...\n";

// Dữ liệu tỉnh/thành phố
$provinces = [
    ['code' => '01', 'name' => 'Thành phố Hà Nội'],
    ['code' => '02', 'name' => 'Tỉnh Hà Giang'],
    ['code' => '04', 'name' => 'Tỉnh Cao Bằng'],
    ['code' => '06', 'name' => 'Tỉnh Bắc Kạn'],
    ['code' => '08', 'name' => 'Tỉnh Tuyên Quang'],
    ['code' => '10', 'name' => 'Tỉnh Lào Cai'],
    ['code' => '11', 'name' => 'Tỉnh Điện Biên'],
    ['code' => '12', 'name' => 'Tỉnh Lai Châu'],
    ['code' => '14', 'name' => 'Tỉnh Sơn La'],
    ['code' => '15', 'name' => 'Tỉnh Yên Bái'],
    ['code' => '17', 'name' => 'Tỉnh Hoà Bình'],
    ['code' => '19', 'name' => 'Tỉnh Thái Nguyên'],
    ['code' => '20', 'name' => 'Tỉnh Lạng Sơn'],
    ['code' => '22', 'name' => 'Tỉnh Quảng Ninh'],
    ['code' => '24', 'name' => 'Tỉnh Bắc Giang'],
    ['code' => '25', 'name' => 'Tỉnh Phú Thọ'],
    ['code' => '26', 'name' => 'Tỉnh Vĩnh Phúc'],
    ['code' => '27', 'name' => 'Tỉnh Bắc Ninh'],
    ['code' => '30', 'name' => 'Tỉnh Hải Dương'],
    ['code' => '31', 'name' => 'Thành phố Hải Phòng'],
    ['code' => '33', 'name' => 'Tỉnh Hưng Yên'],
    ['code' => '34', 'name' => 'Tỉnh Thái Bình'],
    ['code' => '35', 'name' => 'Tỉnh Hà Nam'],
    ['code' => '36', 'name' => 'Tỉnh Nam Định'],
    ['code' => '37', 'name' => 'Tỉnh Ninh Bình'],
    ['code' => '38', 'name' => 'Tỉnh Thanh Hóa'],
    ['code' => '40', 'name' => 'Tỉnh Nghệ An'],
    ['code' => '42', 'name' => 'Tỉnh Hà Tĩnh'],
    ['code' => '44', 'name' => 'Tỉnh Quảng Bình'],
    ['code' => '45', 'name' => 'Tỉnh Quảng Trị'],
    ['code' => '46', 'name' => 'Tỉnh Thừa Thiên Huế'],
    ['code' => '48', 'name' => 'Thành phố Đà Nẵng'],
    ['code' => '49', 'name' => 'Tỉnh Quảng Nam'],
    ['code' => '51', 'name' => 'Tỉnh Quảng Ngãi'],
    ['code' => '52', 'name' => 'Tỉnh Bình Định'],
    ['code' => '54', 'name' => 'Tỉnh Phú Yên'],
    ['code' => '56', 'name' => 'Tỉnh Khánh Hòa'],
    ['code' => '58', 'name' => 'Tỉnh Ninh Thuận'],
    ['code' => '60', 'name' => 'Tỉnh Bình Thuận'],
    ['code' => '62', 'name' => 'Tỉnh Kon Tum'],
    ['code' => '64', 'name' => 'Tỉnh Gia Lai'],
    ['code' => '66', 'name' => 'Tỉnh Đắk Lắk'],
    ['code' => '67', 'name' => 'Tỉnh Đắk Nông'],
    ['code' => '68', 'name' => 'Tỉnh Lâm Đồng'],
    ['code' => '70', 'name' => 'Tỉnh Bình Phước'],
    ['code' => '72', 'name' => 'Tỉnh Tây Ninh'],
    ['code' => '74', 'name' => 'Tỉnh Bình Dương'],
    ['code' => '75', 'name' => 'Tỉnh Đồng Nai'],
    ['code' => '77', 'name' => 'Tỉnh Bà Rịa - Vũng Tàu'],
    ['code' => '79', 'name' => 'Thành phố Hồ Chí Minh'],
    ['code' => '80', 'name' => 'Tỉnh Long An'],
    ['code' => '82', 'name' => 'Tỉnh Tiền Giang'],
    ['code' => '83', 'name' => 'Tỉnh Bến Tre'],
    ['code' => '84', 'name' => 'Tỉnh Trà Vinh'],
    ['code' => '86', 'name' => 'Tỉnh Vĩnh Long'],
    ['code' => '87', 'name' => 'Tỉnh Đồng Tháp'],
    ['code' => '89', 'name' => 'Tỉnh An Giang'],
    ['code' => '91', 'name' => 'Tỉnh Kiên Giang'],
    ['code' => '92', 'name' => 'Thành phố Cần Thơ'],
    ['code' => '93', 'name' => 'Tỉnh Hậu Giang'],
    ['code' => '94', 'name' => 'Tỉnh Sóc Trăng'],
    ['code' => '95', 'name' => 'Tỉnh Bạc Liêu'],
    ['code' => '96', 'name' => 'Tỉnh Cà Mau']
];

function generateCodeName($name) {
    $codeName = strtolower($name);
    $codeName = str_replace(['Tỉnh ', 'Thành phố ', 'Quận ', 'Huyện ', 'Phường ', 'Xã ', 'Thị trấn '], '', $codeName);
    $codeName = removeVietnameseAccents($codeName);
    $codeName = preg_replace('/[^a-z0-9\s]/', '', $codeName);
    $codeName = preg_replace('/\s+/', '-', trim($codeName));
    return $codeName;
}

function removeVietnameseAccents($str) {
    $search = ['à','á','ạ','ả','ã','â','ầ','ấ','ậ','ẩ','ẫ','ă','ằ','ắ','ặ','ẳ','ẵ',
               'è','é','ẹ','ẻ','ẽ','ê','ề','ế','ệ','ể','ễ',
               'ì','í','ị','ỉ','ĩ',
               'ò','ó','ọ','ỏ','õ','ô','ồ','ố','ộ','ổ','ỗ','ơ','ờ','ớ','ợ','ở','ỡ',
               'ù','ú','ụ','ủ','ũ','ư','ừ','ứ','ự','ử','ữ',
               'ỳ','ý','ỵ','ỷ','ỹ','đ'];
    
    $replace = ['a','a','a','a','a','a','a','a','a','a','a','a','a','a','a','a','a',
                'e','e','e','e','e','e','e','e','e','e','e',
                'i','i','i','i','i',
                'o','o','o','o','o','o','o','o','o','o','o','o','o','o','o','o','o',
                'u','u','u','u','u','u','u','u','u','u','u',
                'y','y','y','y','y','d'];
    
    return str_replace($search, $replace, $str);
}

try {
    // Cập nhật tỉnh
    echo "Cập nhật tỉnh/thành phố...\n";
    foreach ($provinces as $province) {
        Province::updateOrCreate(
            ['code' => $province['code']],
            [
                'name' => $province['name'],
                'full_name' => $province['name'],
                'code_name' => generateCodeName($province['name']),
                'administrative_unit_id' => 1,
                'administrative_region_id' => 1
            ]
        );
    }

    // Tạo quận/huyện
    echo "Tạo quận/huyện...\n";
    $allProvinces = Province::all();
      foreach ($allProvinces as $province) {
        $existingDistricts = District::where('province_code', $province->code)->count();
        
        if ($existingDistricts < 3) {
            $districtCount = rand(5, 8);
            for ($i = 1; $i <= $districtCount; $i++) {
                $districtCode = $province->code . sprintf('%02d', $i);
                
                if (strpos($province->name, 'Thành phố') !== false) {
                    $districtName = ($i <= 3) ? "Quận $i" : "Huyện " . chr(64 + $i);
                } else {
                    $districtName = "Huyện " . chr(64 + $i);
                }
                
                District::updateOrCreate(
                    ['code' => $districtCode],
                    [
                        'name' => $districtName,
                        'province_code' => $province->code,
                        'full_name' => $districtName,
                        'code_name' => generateCodeName($districtName),
                        'administrative_unit_id' => 1
                    ]
                );
            }
        }
    }

    // Tạo phường/xã
    echo "Tạo phường/xã...\n";
    $allDistricts = District::all();
      foreach ($allDistricts as $district) {
        $existingWards = Ward::where('district_code', $district->code)->count();
        
        if ($existingWards < 5) {
            $wardCount = rand(8, 12);
            
            for ($i = 1; $i <= $wardCount; $i++) {
                $wardCode = $district->code . sprintf('%03d', $i);
                
                if (strpos($district->name, 'Quận') !== false) {
                    $wardName = "Phường $i";
                } else {
                    $wardName = ($i <= 2) ? "Thị trấn " . chr(64 + $i) : "Xã " . chr(64 + $i);
                }
                
                Ward::updateOrCreate(
                    ['code' => $wardCode],
                    [
                        'name' => $wardName,
                        'district_code' => $district->code,
                        'full_name' => $wardName,
                        'code_name' => generateCodeName($wardName),
                        'administrative_unit_id' => 1
                    ]
                );
            }
        }
    }

    // Thống kê
    $finalProvinces = Province::count();
    $finalDistricts = District::count();
    $finalWards = Ward::count();
    
    echo "Hoàn tất cập nhật dữ liệu địa chỉ!\n";
    echo "- Tỉnh/thành phố: $finalProvinces\n";
    echo "- Quận/huyện: $finalDistricts\n";
    echo "- Phường/xã: $finalWards\n";
    
} catch (Exception $e) {
    echo "Lỗi: " . $e->getMessage() . "\n";
}
