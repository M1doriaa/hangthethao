<?php

require_once 'vendor/autoload.php';

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

echo "Bắt đầu import dữ liệu địa chỉ Việt Nam hoàn chỉnh...\n";

// Dữ liệu 63 tỉnh/thành phố Việt Nam
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

// Dữ liệu quận/huyện mẫu cho một số tỉnh/thành phố lớn
$districts = [
    // Hà Nội
    ['code' => '001', 'name' => 'Quận Ba Đình', 'province_code' => '01'],
    ['code' => '002', 'name' => 'Quận Hoàn Kiếm', 'province_code' => '01'],
    ['code' => '003', 'name' => 'Quận Tây Hồ', 'province_code' => '01'],
    ['code' => '004', 'name' => 'Quận Long Biên', 'province_code' => '01'],
    ['code' => '005', 'name' => 'Quận Cầu Giấy', 'province_code' => '01'],
    ['code' => '006', 'name' => 'Quận Đống Đa', 'province_code' => '01'],
    ['code' => '007', 'name' => 'Quận Hai Bà Trưng', 'province_code' => '01'],
    ['code' => '008', 'name' => 'Quận Hoàng Mai', 'province_code' => '01'],
    ['code' => '009', 'name' => 'Quận Thanh Xuân', 'province_code' => '01'],
    ['code' => '010', 'name' => 'Huyện Sóc Sơn', 'province_code' => '01'],
    ['code' => '011', 'name' => 'Huyện Đông Anh', 'province_code' => '01'],
    ['code' => '012', 'name' => 'Huyện Gia Lâm', 'province_code' => '01'],
    ['code' => '013', 'name' => 'Quận Nam Từ Liêm', 'province_code' => '01'],
    ['code' => '014', 'name' => 'Huyện Thanh Trì', 'province_code' => '01'],
    ['code' => '015', 'name' => 'Quận Bắc Từ Liêm', 'province_code' => '01'],
    ['code' => '016', 'name' => 'Huyện Mê Linh', 'province_code' => '01'],
    ['code' => '017', 'name' => 'Quận Hà Đông', 'province_code' => '01'],
    ['code' => '018', 'name' => 'Thị xã Sơn Tây', 'province_code' => '01'],
    ['code' => '019', 'name' => 'Huyện Ba Vì', 'province_code' => '01'],
    ['code' => '020', 'name' => 'Huyện Phúc Thọ', 'province_code' => '01'],
    ['code' => '021', 'name' => 'Huyện Đan Phượng', 'province_code' => '01'],
    ['code' => '022', 'name' => 'Huyện Hoài Đức', 'province_code' => '01'],
    ['code' => '023', 'name' => 'Huyện Quốc Oai', 'province_code' => '01'],
    ['code' => '024', 'name' => 'Huyện Thạch Thất', 'province_code' => '01'],
    ['code' => '025', 'name' => 'Huyện Chương Mỹ', 'province_code' => '01'],
    ['code' => '026', 'name' => 'Huyện Thanh Oai', 'province_code' => '01'],
    ['code' => '027', 'name' => 'Huyện Thường Tín', 'province_code' => '01'],
    ['code' => '028', 'name' => 'Huyện Phú Xuyên', 'province_code' => '01'],
    ['code' => '029', 'name' => 'Huyện Ứng Hòa', 'province_code' => '01'],
    ['code' => '030', 'name' => 'Huyện Mỹ Đức', 'province_code' => '01'],

    // TP Hồ Chí Minh
    ['code' => '760', 'name' => 'Quận 1', 'province_code' => '79'],
    ['code' => '761', 'name' => 'Quận 12', 'province_code' => '79'],
    ['code' => '762', 'name' => 'Quận Gò Vấp', 'province_code' => '79'],
    ['code' => '763', 'name' => 'Quận Bình Thạnh', 'province_code' => '79'],
    ['code' => '764', 'name' => 'Quận Tân Bình', 'province_code' => '79'],
    ['code' => '765', 'name' => 'Quận Tân Phú', 'province_code' => '79'],
    ['code' => '766', 'name' => 'Quận Phú Nhuận', 'province_code' => '79'],
    ['code' => '767', 'name' => 'Thành phố Thủ Đức', 'province_code' => '79'],
    ['code' => '768', 'name' => 'Quận 3', 'province_code' => '79'],
    ['code' => '769', 'name' => 'Quận 10', 'province_code' => '79'],
    ['code' => '770', 'name' => 'Quận 11', 'province_code' => '79'],
    ['code' => '771', 'name' => 'Quận 4', 'province_code' => '79'],
    ['code' => '772', 'name' => 'Quận 5', 'province_code' => '79'],
    ['code' => '773', 'name' => 'Quận 6', 'province_code' => '79'],
    ['code' => '774', 'name' => 'Quận 8', 'province_code' => '79'],
    ['code' => '775', 'name' => 'Quận 7', 'province_code' => '79'],
    ['code' => '776', 'name' => 'Huyện Củ Chi', 'province_code' => '79'],
    ['code' => '777', 'name' => 'Huyện Hóc Môn', 'province_code' => '79'],
    ['code' => '778', 'name' => 'Huyện Bình Chánh', 'province_code' => '79'],
    ['code' => '779', 'name' => 'Huyện Nhà Bè', 'province_code' => '79'],
    ['code' => '780', 'name' => 'Huyện Cần Giờ', 'province_code' => '79'],

    // Đà Nẵng
    ['code' => '490', 'name' => 'Quận Liên Chiểu', 'province_code' => '48'],
    ['code' => '491', 'name' => 'Quận Thanh Khê', 'province_code' => '48'],
    ['code' => '492', 'name' => 'Quận Hải Châu', 'province_code' => '48'],
    ['code' => '493', 'name' => 'Quận Sơn Trà', 'province_code' => '48'],
    ['code' => '494', 'name' => 'Quận Ngũ Hành Sơn', 'province_code' => '48'],
    ['code' => '495', 'name' => 'Quận Cẩm Lệ', 'province_code' => '48'],
    ['code' => '497', 'name' => 'Huyện Hòa Vang', 'province_code' => '48'],
    ['code' => '498', 'name' => 'Huyện Hoàng Sa', 'province_code' => '48'],

    // Hải Phòng
    ['code' => '303', 'name' => 'Quận Hồng Bàng', 'province_code' => '31'],
    ['code' => '304', 'name' => 'Quận Ngô Quyền', 'province_code' => '31'],
    ['code' => '305', 'name' => 'Quận Lê Chân', 'province_code' => '31'],
    ['code' => '306', 'name' => 'Quận Hải An', 'province_code' => '31'],
    ['code' => '307', 'name' => 'Quận Kiến An', 'province_code' => '31'],
    ['code' => '308', 'name' => 'Quận Đồ Sơn', 'province_code' => '31'],
    ['code' => '309', 'name' => 'Quận Dương Kinh', 'province_code' => '31'],
    ['code' => '311', 'name' => 'Huyện Thuỷ Nguyên', 'province_code' => '31'],
    ['code' => '312', 'name' => 'Huyện An Dương', 'province_code' => '31'],
    ['code' => '313', 'name' => 'Huyện An Lão', 'province_code' => '31'],
    ['code' => '314', 'name' => 'Huyện Kiến Thuỵ', 'province_code' => '31'],
    ['code' => '315', 'name' => 'Huyện Tiên Lãng', 'province_code' => '31'],
    ['code' => '316', 'name' => 'Huyện Vĩnh Bảo', 'province_code' => '31'],
    ['code' => '317', 'name' => 'Huyện Cát Hải', 'province_code' => '31'],
    ['code' => '318', 'name' => 'Huyện Bạch Long Vĩ', 'province_code' => '31'],

    // Cần Thơ
    ['code' => '916', 'name' => 'Quận Ninh Kiều', 'province_code' => '92'],
    ['code' => '917', 'name' => 'Quận Ô Môn', 'province_code' => '92'],
    ['code' => '918', 'name' => 'Quận Bình Thuỷ', 'province_code' => '92'],
    ['code' => '919', 'name' => 'Quận Cái Răng', 'province_code' => '92'],
    ['code' => '923', 'name' => 'Quận Thốt Nốt', 'province_code' => '92'],
    ['code' => '924', 'name' => 'Huyện Vĩnh Thạnh', 'province_code' => '92'],
    ['code' => '925', 'name' => 'Huyện Cờ Đỏ', 'province_code' => '92'],
    ['code' => '926', 'name' => 'Huyện Phong Điền', 'province_code' => '92'],
    ['code' => '927', 'name' => 'Huyện Thới Lai', 'province_code' => '92'],
];

// Dữ liệu phường/xã mẫu cho một số quận/huyện
$wards = [
    // Quận Ba Đình, Hà Nội
    ['code' => '00001', 'name' => 'Phường Phúc Xá', 'district_code' => '001'],
    ['code' => '00004', 'name' => 'Phường Trúc Bạch', 'district_code' => '001'],
    ['code' => '00006', 'name' => 'Phường Vĩnh Phúc', 'district_code' => '001'],
    ['code' => '00007', 'name' => 'Phường Cống Vị', 'district_code' => '001'],
    ['code' => '00008', 'name' => 'Phường Liễu Giai', 'district_code' => '001'],
    ['code' => '00010', 'name' => 'Phường Nguyễn Trung Trực', 'district_code' => '001'],
    ['code' => '00013', 'name' => 'Phường Quán Thánh', 'district_code' => '001'],
    ['code' => '00016', 'name' => 'Phường Ngọc Hà', 'district_code' => '001'],
    ['code' => '00019', 'name' => 'Phường Điện Biên', 'district_code' => '001'],
    ['code' => '00022', 'name' => 'Phường Đội Cấn', 'district_code' => '001'],
    ['code' => '00025', 'name' => 'Phường Ngọc Khánh', 'district_code' => '001'],
    ['code' => '00028', 'name' => 'Phường Kim Mã', 'district_code' => '001'],
    ['code' => '00031', 'name' => 'Phường Giảng Võ', 'district_code' => '001'],
    ['code' => '00034', 'name' => 'Phường Thành Công', 'district_code' => '001'],

    // Quận Hoàn Kiếm, Hà Nội
    ['code' => '00037', 'name' => 'Phường Phúc Tấn', 'district_code' => '002'],
    ['code' => '00040', 'name' => 'Phường Đồng Xuân', 'district_code' => '002'],
    ['code' => '00043', 'name' => 'Phường Hàng Mã', 'district_code' => '002'],
    ['code' => '00046', 'name' => 'Phường Hàng Buồm', 'district_code' => '002'],
    ['code' => '00049', 'name' => 'Phường Hàng Đào', 'district_code' => '002'],
    ['code' => '00052', 'name' => 'Phường Hàng Bồ', 'district_code' => '002'],
    ['code' => '00055', 'name' => 'Phường Cửa Đông', 'district_code' => '002'],
    ['code' => '00058', 'name' => 'Phường Lý Thái Tổ', 'district_code' => '002'],
    ['code' => '00061', 'name' => 'Phường Hàng Bạc', 'district_code' => '002'],
    ['code' => '00064', 'name' => 'Phường Hàng Gai', 'district_code' => '002'],
    ['code' => '00067', 'name' => 'Phường Chương Dương Độ', 'district_code' => '002'],
    ['code' => '00070', 'name' => 'Phường Hàng Trống', 'district_code' => '002'],
    ['code' => '00073', 'name' => 'Phường Cửa Nam', 'district_code' => '002'],
    ['code' => '00076', 'name' => 'Phường Hàng Bông', 'district_code' => '002'],
    ['code' => '00079', 'name' => 'Phường Tràng Tiền', 'district_code' => '002'],
    ['code' => '00082', 'name' => 'Phường Trần Hưng Đạo', 'district_code' => '002'],
    ['code' => '00085', 'name' => 'Phường Phan Chu Trinh', 'district_code' => '002'],
    ['code' => '00088', 'name' => 'Phường Hàng Bài', 'district_code' => '002'],

    // Quận 1, TP HCM
    ['code' => '26734', 'name' => 'Phường Tân Định', 'district_code' => '760'],
    ['code' => '26737', 'name' => 'Phường Đa Kao', 'district_code' => '760'],
    ['code' => '26740', 'name' => 'Phường Bến Nghé', 'district_code' => '760'],
    ['code' => '26743', 'name' => 'Phường Bến Thành', 'district_code' => '760'],
    ['code' => '26746', 'name' => 'Phường Nguyễn Thái Bình', 'district_code' => '760'],
    ['code' => '26749', 'name' => 'Phường Phạm Ngũ Lão', 'district_code' => '760'],
    ['code' => '26752', 'name' => 'Phường Cầu Ông Lãnh', 'district_code' => '760'],
    ['code' => '26755', 'name' => 'Phường Cô Giang', 'district_code' => '760'],
    ['code' => '26758', 'name' => 'Phường Nguyễn Cư Trinh', 'district_code' => '760'],
    ['code' => '26761', 'name' => 'Phường Cầu Kho', 'district_code' => '760'],

    // Quận Hải Châu, Đà Nẵng
    ['code' => '20194', 'name' => 'Phường Thạch Thang', 'district_code' => '492'],
    ['code' => '20195', 'name' => 'Phường Hải Châu I', 'district_code' => '492'],
    ['code' => '20197', 'name' => 'Phường Hải Châu II', 'district_code' => '492'],
    ['code' => '20200', 'name' => 'Phường Phước Ninh', 'district_code' => '492'],
    ['code' => '20203', 'name' => 'Phường Hòa Thuận Tây', 'district_code' => '492'],
    ['code' => '20206', 'name' => 'Phường Hòa Thuận Đông', 'district_code' => '492'],
    ['code' => '20207', 'name' => 'Phường Nam Dương', 'district_code' => '492'],
    ['code' => '20209', 'name' => 'Phường Bình Hiên', 'district_code' => '492'],
    ['code' => '20212', 'name' => 'Phường Bình Thuận', 'district_code' => '492'],
    ['code' => '20215', 'name' => 'Phường Hòa Cường Bắc', 'district_code' => '492'],
    ['code' => '20218', 'name' => 'Phường Hòa Cường Nam', 'district_code' => '492'],
    ['code' => '20221', 'name' => 'Phường Thanh Bình', 'district_code' => '492'],
    ['code' => '20224', 'name' => 'Phường Thuận Phước', 'district_code' => '492'],
];

try {
    // Xóa dữ liệu cũ
    echo "Đang xóa dữ liệu cũ...\n";
    Capsule::table('wards')->delete();
    Capsule::table('districts')->delete();
    Capsule::table('provinces')->delete();

    // Import tỉnh/thành phố
    echo "Đang import 63 tỉnh/thành phố...\n";
    $provinceData = [];
    foreach ($provinces as $province) {
        $provinceData[] = [
            'code' => $province['code'],
            'name' => $province['name'],
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ];
    }
    Capsule::table('provinces')->insert($provinceData);

    // Import quận/huyện
    echo "Đang import quận/huyện...\n";
    $districtData = [];
    foreach ($districts as $district) {
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
    Capsule::table('districts')->insert($districtData);

    // Import phường/xã
    echo "Đang import phường/xã...\n";
    $wardData = [];
    foreach ($wards as $ward) {
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
    
    if (!empty($wardData)) {
        Capsule::table('wards')->insert($wardData);
    }

    // Tạo thêm dữ liệu mẫu cho các tỉnh/thành phố khác
    echo "Đang tạo dữ liệu mẫu cho các tỉnh còn lại...\n";
    
    $allProvinces = Capsule::table('provinces')->get();
    $additionalDistricts = [];
    $additionalWards = [];
    
    foreach ($allProvinces as $province) {
        // Kiểm tra xem tỉnh này đã có quận/huyện chưa
        $existingDistricts = Capsule::table('districts')
            ->where('province_id', $province->id)
            ->count();
            
        if ($existingDistricts == 0) {
            // Tạo 3-5 quận/huyện mẫu cho mỗi tỉnh
            $districtCount = rand(3, 5);
            for ($i = 1; $i <= $districtCount; $i++) {
                $districtCode = $province->code . sprintf('%02d', $i);
                $districtName = '';
                
                if (strpos($province->name, 'Thành phố') !== false) {
                    $districtName = ($i <= 2) ? "Quận $i" : "Huyện " . chr(64 + $i);
                } else {
                    $districtName = "Huyện " . chr(64 + $i);
                }
                
                $additionalDistricts[] = [
                    'code' => $districtCode,
                    'name' => $districtName,
                    'province_id' => $province->id,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ];
            }
        }
    }
    
    // Insert additional districts
    if (!empty($additionalDistricts)) {
        $chunks = array_chunk($additionalDistricts, 50);
        foreach ($chunks as $chunk) {
            Capsule::table('districts')->insert($chunk);
        }
    }
    
    // Tạo phường/xã cho tất cả quận/huyện
    $allDistricts = Capsule::table('districts')->get();
    foreach ($allDistricts as $district) {
        $existingWards = Capsule::table('wards')
            ->where('district_id', $district->id)
            ->count();
            
        if ($existingWards == 0) {
            // Tạo 5-10 phường/xã cho mỗi quận/huyện
            $wardCount = rand(5, 10);
            for ($i = 1; $i <= $wardCount; $i++) {
                $wardCode = $district->code . sprintf('%03d', $i);
                $wardName = '';
                
                if (strpos($district->name, 'Quận') !== false) {
                    $wardName = "Phường $i";
                } else {
                    $wardName = ($i <= 2) ? "Thị trấn " . chr(64 + $i) : "Xã " . chr(64 + $i);
                }
                
                $additionalWards[] = [
                    'code' => $wardCode,
                    'name' => $wardName,
                    'district_id' => $district->id,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ];
            }
        }
    }
    
    // Insert additional wards
    if (!empty($additionalWards)) {
        $chunks = array_chunk($additionalWards, 100);
        foreach ($chunks as $chunk) {
            Capsule::table('wards')->insert($chunk);
        }
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
