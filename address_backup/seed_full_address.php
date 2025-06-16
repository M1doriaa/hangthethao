<?php

require_once 'vendor/autoload.php';

use App\Models\Province;
use App\Models\District;
use App\Models\Ward;
use Illuminate\Support\Facades\DB;

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "Bắt đầu import dữ liệu địa chỉ Việt Nam...\n";

try {
    // Xóa dữ liệu cũ
    DB::statement('SET FOREIGN_KEY_CHECKS=0;');
    Ward::truncate();
    District::truncate();
    Province::truncate();
    DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    
    echo "Đã xóa dữ liệu cũ\n";

    // Dữ liệu tỉnh/thành phố đầy đủ 63 tỉnh thành Việt Nam
    $provinces = [
        ['code' => '01', 'name' => 'Hà Nội', 'full_name' => 'Thành phố Hà Nội', 'code_name' => 'ha-noi'],
        ['code' => '79', 'name' => 'TP. Hồ Chí Minh', 'full_name' => 'Thành phố Hồ Chí Minh', 'code_name' => 'ho-chi-minh'],
        ['code' => '48', 'name' => 'Đà Nẵng', 'full_name' => 'Thành phố Đà Nẵng', 'code_name' => 'da-nang'],
        ['code' => '31', 'name' => 'Hải Phòng', 'full_name' => 'Thành phố Hải Phòng', 'code_name' => 'hai-phong'],
        ['code' => '92', 'name' => 'Cần Thơ', 'full_name' => 'Thành phố Cần Thơ', 'code_name' => 'can-tho'],
        ['code' => '02', 'name' => 'Hà Giang', 'full_name' => 'Tỉnh Hà Giang', 'code_name' => 'ha-giang'],
        ['code' => '04', 'name' => 'Cao Bằng', 'full_name' => 'Tỉnh Cao Bằng', 'code_name' => 'cao-bang'],
        ['code' => '06', 'name' => 'Bắc Kạn', 'full_name' => 'Tỉnh Bắc Kạn', 'code_name' => 'bac-kan'],
        ['code' => '08', 'name' => 'Tuyên Quang', 'full_name' => 'Tỉnh Tuyên Quang', 'code_name' => 'tuyen-quang'],
        ['code' => '10', 'name' => 'Lào Cai', 'full_name' => 'Tỉnh Lào Cai', 'code_name' => 'lao-cai'],
        ['code' => '11', 'name' => 'Điện Biên', 'full_name' => 'Tỉnh Điện Biên', 'code_name' => 'dien-bien'],
        ['code' => '12', 'name' => 'Lai Châu', 'full_name' => 'Tỉnh Lai Châu', 'code_name' => 'lai-chau'],
        ['code' => '14', 'name' => 'Sơn La', 'full_name' => 'Tỉnh Sơn La', 'code_name' => 'son-la'],
        ['code' => '15', 'name' => 'Yên Bái', 'full_name' => 'Tỉnh Yên Bái', 'code_name' => 'yen-bai'],
        ['code' => '17', 'name' => 'Hoà Bình', 'full_name' => 'Tỉnh Hoà Bình', 'code_name' => 'hoa-binh'],
        ['code' => '19', 'name' => 'Thái Nguyên', 'full_name' => 'Tỉnh Thái Nguyên', 'code_name' => 'thai-nguyen'],
        ['code' => '20', 'name' => 'Lạng Sơn', 'full_name' => 'Tỉnh Lạng Sơn', 'code_name' => 'lang-son'],
        ['code' => '22', 'name' => 'Quảng Ninh', 'full_name' => 'Tỉnh Quảng Ninh', 'code_name' => 'quang-ninh'],
        ['code' => '24', 'name' => 'Bắc Giang', 'full_name' => 'Tỉnh Bắc Giang', 'code_name' => 'bac-giang'],
        ['code' => '25', 'name' => 'Phú Thọ', 'full_name' => 'Tỉnh Phú Thọ', 'code_name' => 'phu-tho'],
        ['code' => '26', 'name' => 'Vĩnh Phúc', 'full_name' => 'Tỉnh Vĩnh Phúc', 'code_name' => 'vinh-phuc'],
        ['code' => '27', 'name' => 'Bắc Ninh', 'full_name' => 'Tỉnh Bắc Ninh', 'code_name' => 'bac-ninh'],
        ['code' => '30', 'name' => 'Hải Dương', 'full_name' => 'Tỉnh Hải Dương', 'code_name' => 'hai-duong'],
        ['code' => '33', 'name' => 'Hưng Yên', 'full_name' => 'Tỉnh Hưng Yên', 'code_name' => 'hung-yen'],
        ['code' => '34', 'name' => 'Thái Bình', 'full_name' => 'Tỉnh Thái Bình', 'code_name' => 'thai-binh'],
        ['code' => '35', 'name' => 'Hà Nam', 'full_name' => 'Tỉnh Hà Nam', 'code_name' => 'ha-nam'],
        ['code' => '36', 'name' => 'Nam Định', 'full_name' => 'Tỉnh Nam Định', 'code_name' => 'nam-dinh'],
        ['code' => '37', 'name' => 'Ninh Bình', 'full_name' => 'Tỉnh Ninh Bình', 'code_name' => 'ninh-binh'],
        ['code' => '38', 'name' => 'Thanh Hóa', 'full_name' => 'Tỉnh Thanh Hóa', 'code_name' => 'thanh-hoa'],
        ['code' => '40', 'name' => 'Nghệ An', 'full_name' => 'Tỉnh Nghệ An', 'code_name' => 'nghe-an'],
        ['code' => '42', 'name' => 'Hà Tĩnh', 'full_name' => 'Tỉnh Hà Tĩnh', 'code_name' => 'ha-tinh'],
        ['code' => '44', 'name' => 'Quảng Bình', 'full_name' => 'Tỉnh Quảng Bình', 'code_name' => 'quang-binh'],
        ['code' => '45', 'name' => 'Quảng Trị', 'full_name' => 'Tỉnh Quảng Trị', 'code_name' => 'quang-tri'],
        ['code' => '46', 'name' => 'Thừa Thiên Huế', 'full_name' => 'Tỉnh Thừa Thiên Huế', 'code_name' => 'thua-thien-hue'],
        ['code' => '49', 'name' => 'Quảng Nam', 'full_name' => 'Tỉnh Quảng Nam', 'code_name' => 'quang-nam'],
        ['code' => '51', 'name' => 'Quảng Ngãi', 'full_name' => 'Tỉnh Quảng Ngãi', 'code_name' => 'quang-ngai'],
        ['code' => '52', 'name' => 'Bình Định', 'full_name' => 'Tỉnh Bình Định', 'code_name' => 'binh-dinh'],
        ['code' => '54', 'name' => 'Phú Yên', 'full_name' => 'Tỉnh Phú Yên', 'code_name' => 'phu-yen'],
        ['code' => '56', 'name' => 'Khánh Hòa', 'full_name' => 'Tỉnh Khánh Hòa', 'code_name' => 'khanh-hoa'],
        ['code' => '58', 'name' => 'Ninh Thuận', 'full_name' => 'Tỉnh Ninh Thuận', 'code_name' => 'ninh-thuan'],
        ['code' => '60', 'name' => 'Bình Thuận', 'full_name' => 'Tỉnh Bình Thuận', 'code_name' => 'binh-thuan'],
        ['code' => '62', 'name' => 'Kon Tum', 'full_name' => 'Tỉnh Kon Tum', 'code_name' => 'kon-tum'],
        ['code' => '64', 'name' => 'Gia Lai', 'full_name' => 'Tỉnh Gia Lai', 'code_name' => 'gia-lai'],
        ['code' => '66', 'name' => 'Đắk Lắk', 'full_name' => 'Tỉnh Đắk Lắk', 'code_name' => 'dak-lak'],
        ['code' => '67', 'name' => 'Đắk Nông', 'full_name' => 'Tỉnh Đắk Nông', 'code_name' => 'dak-nong'],
        ['code' => '68', 'name' => 'Lâm Đồng', 'full_name' => 'Tỉnh Lâm Đồng', 'code_name' => 'lam-dong'],
        ['code' => '70', 'name' => 'Bình Phước', 'full_name' => 'Tỉnh Bình Phước', 'code_name' => 'binh-phuoc'],
        ['code' => '72', 'name' => 'Tây Ninh', 'full_name' => 'Tỉnh Tây Ninh', 'code_name' => 'tay-ninh'],
        ['code' => '74', 'name' => 'Bình Dương', 'full_name' => 'Tỉnh Bình Dương', 'code_name' => 'binh-duong'],
        ['code' => '75', 'name' => 'Đồng Nai', 'full_name' => 'Tỉnh Đồng Nai', 'code_name' => 'dong-nai'],
        ['code' => '77', 'name' => 'Bà Rịa - Vũng Tàu', 'full_name' => 'Tỉnh Bà Rịa - Vũng Tàu', 'code_name' => 'ba-ria-vung-tau'],
        ['code' => '80', 'name' => 'Long An', 'full_name' => 'Tỉnh Long An', 'code_name' => 'long-an'],
        ['code' => '82', 'name' => 'Tiền Giang', 'full_name' => 'Tỉnh Tiền Giang', 'code_name' => 'tien-giang'],
        ['code' => '83', 'name' => 'Bến Tre', 'full_name' => 'Tỉnh Bến Tre', 'code_name' => 'ben-tre'],
        ['code' => '84', 'name' => 'Trà Vinh', 'full_name' => 'Tỉnh Trà Vinh', 'code_name' => 'tra-vinh'],
        ['code' => '86', 'name' => 'Vĩnh Long', 'full_name' => 'Tỉnh Vĩnh Long', 'code_name' => 'vinh-long'],
        ['code' => '87', 'name' => 'Đồng Tháp', 'full_name' => 'Tỉnh Đồng Tháp', 'code_name' => 'dong-thap'],
        ['code' => '89', 'name' => 'An Giang', 'full_name' => 'Tỉnh An Giang', 'code_name' => 'an-giang'],
        ['code' => '91', 'name' => 'Kiên Giang', 'full_name' => 'Tỉnh Kiên Giang', 'code_name' => 'kien-giang'],
        ['code' => '93', 'name' => 'Hậu Giang', 'full_name' => 'Tỉnh Hậu Giang', 'code_name' => 'hau-giang'],
        ['code' => '94', 'name' => 'Sóc Trăng', 'full_name' => 'Tỉnh Sóc Trăng', 'code_name' => 'soc-trang'],
        ['code' => '95', 'name' => 'Bạc Liêu', 'full_name' => 'Tỉnh Bạc Liêu', 'code_name' => 'bac-lieu'],
        ['code' => '96', 'name' => 'Cà Mau', 'full_name' => 'Tỉnh Cà Mau', 'code_name' => 'ca-mau'],
    ];

    // Insert provinces
    foreach ($provinces as $province) {
        $province['created_at'] = now();
        $province['updated_at'] = now();
    }
    Province::insert($provinces);
    echo "Đã insert " . count($provinces) . " tỉnh/thành phố\n";

    echo "Import hoàn tất tỉnh/thành phố!\n";
    echo "Bắt đầu import quận/huyện và phường/xã...\n";

} catch (Exception $e) {
    echo "Lỗi: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
}
