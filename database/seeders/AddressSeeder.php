<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Province;
use App\Models\District;
use App\Models\Ward;

class AddressSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Tạo dữ liệu tỉnh/thành phố
        $provinces = [
            [
                'code' => '01',
                'name' => 'Hà Nội',
                'name_en' => 'Hanoi',
                'full_name' => 'Thành phố Hà Nội',
                'full_name_en' => 'Hanoi City',
                'code_name' => 'ha_noi',
                'administrative_unit_id' => 1,
                'administrative_region_id' => 1,
            ],
            [
                'code' => '79',
                'name' => 'Thành phố Hồ Chí Minh',
                'name_en' => 'Ho Chi Minh City',
                'full_name' => 'Thành phố Hồ Chí Minh',
                'full_name_en' => 'Ho Chi Minh City',
                'code_name' => 'ho_chi_minh',
                'administrative_unit_id' => 1,
                'administrative_region_id' => 2,
            ],
            [
                'code' => '48',
                'name' => 'Đà Nẵng',
                'name_en' => 'Da Nang',
                'full_name' => 'Thành phố Đà Nẵng',
                'full_name_en' => 'Da Nang City',
                'code_name' => 'da_nang',
                'administrative_unit_id' => 1,
                'administrative_region_id' => 3,
            ],
            [
                'code' => '31',
                'name' => 'Hải Phòng',
                'name_en' => 'Hai Phong',
                'full_name' => 'Thành phố Hải Phòng',
                'full_name_en' => 'Hai Phong City',
                'code_name' => 'hai_phong',
                'administrative_unit_id' => 1,
                'administrative_region_id' => 1,
            ],
            [
                'code' => '92',
                'name' => 'Cần Thơ',
                'name_en' => 'Can Tho',
                'full_name' => 'Thành phố Cần Thơ',
                'full_name_en' => 'Can Tho City',
                'code_name' => 'can_tho',
                'administrative_unit_id' => 1,
                'administrative_region_id' => 2,
            ],
            [
                'code' => '02',
                'name' => 'Hà Giang',
                'name_en' => 'Ha Giang',
                'full_name' => 'Tỉnh Hà Giang',
                'full_name_en' => 'Ha Giang Province',
                'code_name' => 'ha_giang',
                'administrative_unit_id' => 2,
                'administrative_region_id' => 1,
            ],
            [
                'code' => '04',
                'name' => 'Cao Bằng',
                'name_en' => 'Cao Bang',
                'full_name' => 'Tỉnh Cao Bằng',
                'full_name_en' => 'Cao Bang Province',
                'code_name' => 'cao_bang',
                'administrative_unit_id' => 2,
                'administrative_region_id' => 1,
            ],
            [
                'code' => '06',
                'name' => 'Bắc Kạn',
                'name_en' => 'Bac Kan',
                'full_name' => 'Tỉnh Bắc Kạn',
                'full_name_en' => 'Bac Kan Province',
                'code_name' => 'bac_kan',
                'administrative_unit_id' => 2,
                'administrative_region_id' => 1,
            ],
            [
                'code' => '08',
                'name' => 'Tuyên Quang',
                'name_en' => 'Tuyen Quang',
                'full_name' => 'Tỉnh Tuyên Quang',
                'full_name_en' => 'Tuyen Quang Province',
                'code_name' => 'tuyen_quang',
                'administrative_unit_id' => 2,
                'administrative_region_id' => 1,
            ],
            [
                'code' => '10',
                'name' => 'Lào Cai',
                'name_en' => 'Lao Cai',
                'full_name' => 'Tỉnh Lào Cai',
                'full_name_en' => 'Lao Cai Province',
                'code_name' => 'lao_cai',
                'administrative_unit_id' => 2,
                'administrative_region_id' => 1,
            ],
        ];

        // Tạo dữ liệu quận/huyện
        $districts = [
            // Hà Nội
            [
                'code' => '001',
                'name' => 'Ba Đình',
                'name_en' => 'Ba Dinh',
                'full_name' => 'Quận Ba Đình',
                'full_name_en' => 'Ba Dinh District',
                'code_name' => 'ba_dinh',
                'province_code' => '01',
                'administrative_unit_id' => 1,
            ],
            [
                'code' => '002',
                'name' => 'Hoàn Kiếm',
                'name_en' => 'Hoan Kiem',
                'full_name' => 'Quận Hoàn Kiếm',
                'full_name_en' => 'Hoan Kiem District',
                'code_name' => 'hoan_kiem',
                'province_code' => '01',
                'administrative_unit_id' => 1,
            ],
            [
                'code' => '003',
                'name' => 'Tây Hồ',
                'name_en' => 'Tay Ho',
                'full_name' => 'Quận Tây Hồ',
                'full_name_en' => 'Tay Ho District',
                'code_name' => 'tay_ho',
                'province_code' => '01',
                'administrative_unit_id' => 1,
            ],
            [
                'code' => '004',
                'name' => 'Long Biên',
                'name_en' => 'Long Bien',
                'full_name' => 'Quận Long Biên',
                'full_name_en' => 'Long Bien District',
                'code_name' => 'long_bien',
                'province_code' => '01',
                'administrative_unit_id' => 1,
            ],
            [
                'code' => '005',
                'name' => 'Cầu Giấy',
                'name_en' => 'Cau Giay',
                'full_name' => 'Quận Cầu Giấy',
                'full_name_en' => 'Cau Giay District',
                'code_name' => 'cau_giay',
                'province_code' => '01',
                'administrative_unit_id' => 1,
            ],
            
            // TP. Hồ Chí Minh
            [
                'code' => '760',
                'name' => 'Quận 1',
                'name_en' => 'District 1',
                'full_name' => 'Quận 1',
                'full_name_en' => 'District 1',
                'code_name' => 'quan_1',
                'province_code' => '79',
                'administrative_unit_id' => 1,
            ],
            [
                'code' => '761',
                'name' => 'Quận 2',
                'name_en' => 'District 2',
                'full_name' => 'Quận 2',
                'full_name_en' => 'District 2',
                'code_name' => 'quan_2',
                'province_code' => '79',
                'administrative_unit_id' => 1,
            ],
            [
                'code' => '762',
                'name' => 'Quận 3',
                'name_en' => 'District 3',
                'full_name' => 'Quận 3',
                'full_name_en' => 'District 3',
                'code_name' => 'quan_3',
                'province_code' => '79',
                'administrative_unit_id' => 1,
            ],
            [
                'code' => '763',
                'name' => 'Quận 4',
                'name_en' => 'District 4',
                'full_name' => 'Quận 4',
                'full_name_en' => 'District 4',
                'code_name' => 'quan_4',
                'province_code' => '79',
                'administrative_unit_id' => 1,
            ],
            [
                'code' => '764',
                'name' => 'Quận 5',
                'name_en' => 'District 5',
                'full_name' => 'Quận 5',
                'full_name_en' => 'District 5',
                'code_name' => 'quan_5',
                'province_code' => '79',
                'administrative_unit_id' => 1,
            ],
            
            // Đà Nẵng
            [
                'code' => '490',
                'name' => 'Liên Chiểu',
                'name_en' => 'Lien Chieu',
                'full_name' => 'Quận Liên Chiểu',
                'full_name_en' => 'Lien Chieu District',
                'code_name' => 'lien_chieu',
                'province_code' => '48',
                'administrative_unit_id' => 1,
            ],
            [
                'code' => '491',
                'name' => 'Thanh Khê',
                'name_en' => 'Thanh Khe',
                'full_name' => 'Quận Thanh Khê',
                'full_name_en' => 'Thanh Khe District',
                'code_name' => 'thanh_khe',
                'province_code' => '48',
                'administrative_unit_id' => 1,
            ],
            [
                'code' => '492',
                'name' => 'Hải Châu',
                'name_en' => 'Hai Chau',
                'full_name' => 'Quận Hải Châu',
                'full_name_en' => 'Hai Chau District',
                'code_name' => 'hai_chau',
                'province_code' => '48',
                'administrative_unit_id' => 1,
            ],
        ];

        // Tạo dữ liệu phường/xã
        $wards = [
            // Ba Đình - Hà Nội
            [
                'code' => '00001',
                'name' => 'Phúc Xá',
                'name_en' => 'Phuc Xa',
                'full_name' => 'Phường Phúc Xá',
                'full_name_en' => 'Phuc Xa Ward',
                'code_name' => 'phuc_xa',
                'district_code' => '001',
                'administrative_unit_id' => 1,
            ],
            [
                'code' => '00002',
                'name' => 'Trúc Bạch',
                'name_en' => 'Truc Bach',
                'full_name' => 'Phường Trúc Bạch',
                'full_name_en' => 'Truc Bach Ward',
                'code_name' => 'truc_bach',
                'district_code' => '001',
                'administrative_unit_id' => 1,
            ],
            [
                'code' => '00003',
                'name' => 'Vĩnh Phúc',
                'name_en' => 'Vinh Phuc',
                'full_name' => 'Phường Vĩnh Phúc',
                'full_name_en' => 'Vinh Phuc Ward',
                'code_name' => 'vinh_phuc',
                'district_code' => '001',
                'administrative_unit_id' => 1,
            ],
            
            // Hoàn Kiếm - Hà Nội
            [
                'code' => '00004',
                'name' => 'Chương Dương',
                'name_en' => 'Chuong Duong',
                'full_name' => 'Phường Chương Dương',
                'full_name_en' => 'Chuong Duong Ward',
                'code_name' => 'chuong_duong',
                'district_code' => '002',
                'administrative_unit_id' => 1,
            ],
            [
                'code' => '00005',
                'name' => 'Cửa Đông',
                'name_en' => 'Cua Dong',
                'full_name' => 'Phường Cửa Đông',
                'full_name_en' => 'Cua Dong Ward',
                'code_name' => 'cua_dong',
                'district_code' => '002',
                'administrative_unit_id' => 1,
            ],
            [
                'code' => '00006',
                'name' => 'Cửa Nam',
                'name_en' => 'Cua Nam',
                'full_name' => 'Phường Cửa Nam',
                'full_name_en' => 'Cua Nam Ward',
                'code_name' => 'cua_nam',
                'district_code' => '002',
                'administrative_unit_id' => 1,
            ],
            
            // Quận 1 - TP. Hồ Chí Minh
            [
                'code' => '26734',
                'name' => 'Tân Định',
                'name_en' => 'Tan Dinh',
                'full_name' => 'Phường Tân Định',
                'full_name_en' => 'Tan Dinh Ward',
                'code_name' => 'tan_dinh',
                'district_code' => '760',
                'administrative_unit_id' => 1,
            ],
            [
                'code' => '26735',
                'name' => 'Đa Kao',
                'name_en' => 'Da Kao',
                'full_name' => 'Phường Đa Kao',
                'full_name_en' => 'Da Kao Ward',
                'code_name' => 'da_kao',
                'district_code' => '760',
                'administrative_unit_id' => 1,
            ],
            [
                'code' => '26736',
                'name' => 'Bến Nghé',
                'name_en' => 'Ben Nghe',
                'full_name' => 'Phường Bến Nghé',
                'full_name_en' => 'Ben Nghe Ward',
                'code_name' => 'ben_nghe',
                'district_code' => '760',
                'administrative_unit_id' => 1,
            ],
            [
                'code' => '26737',
                'name' => 'Bến Thành',
                'name_en' => 'Ben Thanh',
                'full_name' => 'Phường Bến Thành',
                'full_name_en' => 'Ben Thanh Ward',
                'code_name' => 'ben_thanh',
                'district_code' => '760',
                'administrative_unit_id' => 1,
            ],
            
            // Quận 2 - TP. Hồ Chí Minh
            [
                'code' => '26740',
                'name' => 'Thủ Thiêm',
                'name_en' => 'Thu Thiem',
                'full_name' => 'Phường Thủ Thiêm',
                'full_name_en' => 'Thu Thiem Ward',
                'code_name' => 'thu_thiem',
                'district_code' => '761',
                'administrative_unit_id' => 1,
            ],
            [
                'code' => '26741',
                'name' => 'An Phú',
                'name_en' => 'An Phu',
                'full_name' => 'Phường An Phú',
                'full_name_en' => 'An Phu Ward',
                'code_name' => 'an_phu',
                'district_code' => '761',
                'administrative_unit_id' => 1,
            ],
            [
                'code' => '26742',
                'name' => 'An Khánh',
                'name_en' => 'An Khanh',
                'full_name' => 'Phường An Khánh',
                'full_name_en' => 'An Khanh Ward',
                'code_name' => 'an_khanh',
                'district_code' => '761',
                'administrative_unit_id' => 1,
            ],
        ];

        // Insert dữ liệu
        foreach ($provinces as $province) {
            Province::create($province);
        }

        foreach ($districts as $district) {
            District::create($district);
        }

        foreach ($wards as $ward) {
            Ward::create($ward);
        }

        $this->command->info('Đã tạo xong dữ liệu địa chỉ mẫu!');
        $this->command->info('Tỉnh/Thành phố: ' . count($provinces));
        $this->command->info('Quận/Huyện: ' . count($districts));
        $this->command->info('Phường/Xã: ' . count($wards));
    }
}
