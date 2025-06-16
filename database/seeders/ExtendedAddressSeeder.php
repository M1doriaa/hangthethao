<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Province;
use App\Models\District;
use App\Models\Ward;

class ExtendedAddressSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Thêm quận/huyện cho TP. Hồ Chí Minh
        $hcmDistricts = [
            [
                'code' => '765',
                'name' => 'Quận 6',
                'name_en' => 'District 6',
                'full_name' => 'Quận 6',
                'full_name_en' => 'District 6',
                'code_name' => 'quan_6',
                'province_code' => '79',
                'administrative_unit_id' => 1,
            ],
            [
                'code' => '766',
                'name' => 'Quận 7',
                'name_en' => 'District 7',
                'full_name' => 'Quận 7',
                'full_name_en' => 'District 7',
                'code_name' => 'quan_7',
                'province_code' => '79',
                'administrative_unit_id' => 1,
            ],
            [
                'code' => '767',
                'name' => 'Quận 8',
                'name_en' => 'District 8',
                'full_name' => 'Quận 8',
                'full_name_en' => 'District 8',
                'code_name' => 'quan_8',
                'province_code' => '79',
                'administrative_unit_id' => 1,
            ],
            [
                'code' => '768',
                'name' => 'Quận 9',
                'name_en' => 'District 9',
                'full_name' => 'Quận 9',
                'full_name_en' => 'District 9',
                'code_name' => 'quan_9',
                'province_code' => '79',
                'administrative_unit_id' => 1,
            ],
            [
                'code' => '769',
                'name' => 'Quận 10',
                'name_en' => 'District 10',
                'full_name' => 'Quận 10',
                'full_name_en' => 'District 10',
                'code_name' => 'quan_10',
                'province_code' => '79',
                'administrative_unit_id' => 1,
            ],
            [
                'code' => '770',
                'name' => 'Quận 11',
                'name_en' => 'District 11',
                'full_name' => 'Quận 11',
                'full_name_en' => 'District 11',
                'code_name' => 'quan_11',
                'province_code' => '79',
                'administrative_unit_id' => 1,
            ],
            [
                'code' => '771',
                'name' => 'Quận 12',
                'name_en' => 'District 12',
                'full_name' => 'Quận 12',
                'full_name_en' => 'District 12',
                'code_name' => 'quan_12',
                'province_code' => '79',
                'administrative_unit_id' => 1,
            ],
            [
                'code' => '772',
                'name' => 'Thủ Đức',
                'name_en' => 'Thu Duc',
                'full_name' => 'Thành phố Thủ Đức',
                'full_name_en' => 'Thu Duc City',
                'code_name' => 'thu_duc',
                'province_code' => '79',
                'administrative_unit_id' => 1,
            ],
            [
                'code' => '773',
                'name' => 'Gò Vấp',
                'name_en' => 'Go Vap',
                'full_name' => 'Quận Gò Vấp',
                'full_name_en' => 'Go Vap District',
                'code_name' => 'go_vap',
                'province_code' => '79',
                'administrative_unit_id' => 1,
            ],
            [
                'code' => '774',
                'name' => 'Bình Thạnh',
                'name_en' => 'Binh Thanh',
                'full_name' => 'Quận Bình Thạnh',
                'full_name_en' => 'Binh Thanh District',
                'code_name' => 'binh_thanh',
                'province_code' => '79',
                'administrative_unit_id' => 1,
            ],
            [
                'code' => '775',
                'name' => 'Tân Bình',
                'name_en' => 'Tan Binh',
                'full_name' => 'Quận Tân Bình',
                'full_name_en' => 'Tan Binh District',
                'code_name' => 'tan_binh',
                'province_code' => '79',
                'administrative_unit_id' => 1,
            ],
            [
                'code' => '776',
                'name' => 'Tân Phú',
                'name_en' => 'Tan Phu',
                'full_name' => 'Quận Tân Phú',
                'full_name_en' => 'Tan Phu District',
                'code_name' => 'tan_phu',
                'province_code' => '79',
                'administrative_unit_id' => 1,
            ],
            [
                'code' => '777',
                'name' => 'Phú Nhuận',
                'name_en' => 'Phu Nhuan',
                'full_name' => 'Quận Phú Nhuận',
                'full_name_en' => 'Phu Nhuan District',
                'code_name' => 'phu_nhuan',
                'province_code' => '79',
                'administrative_unit_id' => 1,
            ],
        ];

        // Thêm phường/xã cho các quận mới
        $newWards = [
            // Quận 3 - TP. Hồ Chí Minh
            [
                'code' => '26743',
                'name' => 'Võ Thị Sáu',
                'name_en' => 'Vo Thi Sau',
                'full_name' => 'Phường Võ Thị Sáu',
                'full_name_en' => 'Vo Thi Sau Ward',
                'code_name' => 'vo_thi_sau',
                'district_code' => '762',
                'administrative_unit_id' => 1,
            ],
            [
                'code' => '26744',
                'name' => 'Đa Kao',
                'name_en' => 'Da Kao',
                'full_name' => 'Phường Đa Kao',
                'full_name_en' => 'Da Kao Ward',
                'code_name' => 'da_kao_q3',
                'district_code' => '762',
                'administrative_unit_id' => 1,
            ],
            [
                'code' => '26745',
                'name' => 'Nguyễn Thái Bình',
                'name_en' => 'Nguyen Thai Binh',
                'full_name' => 'Phường Nguyễn Thái Bình',
                'full_name_en' => 'Nguyen Thai Binh Ward',
                'code_name' => 'nguyen_thai_binh',
                'district_code' => '762',
                'administrative_unit_id' => 1,
            ],
            [
                'code' => '26746',
                'name' => 'Phạm Ngũ Lão',
                'name_en' => 'Pham Ngu Lao',
                'full_name' => 'Phường Phạm Ngũ Lão',
                'full_name_en' => 'Pham Ngu Lao Ward',
                'code_name' => 'pham_ngu_lao',
                'district_code' => '762',
                'administrative_unit_id' => 1,
            ],
            
            // Quận 7 - TP. Hồ Chí Minh
            [
                'code' => '26750',
                'name' => 'Tân Thuận Đông',
                'name_en' => 'Tan Thuan Dong',
                'full_name' => 'Phường Tân Thuận Đông',
                'full_name_en' => 'Tan Thuan Dong Ward',
                'code_name' => 'tan_thuan_dong',
                'district_code' => '766',
                'administrative_unit_id' => 1,
            ],
            [
                'code' => '26751',
                'name' => 'Tân Thuận Tây',
                'name_en' => 'Tan Thuan Tay',
                'full_name' => 'Phường Tân Thuận Tây',
                'full_name_en' => 'Tan Thuan Tay Ward',
                'code_name' => 'tan_thuan_tay',
                'district_code' => '766',
                'administrative_unit_id' => 1,
            ],
            [
                'code' => '26752',
                'name' => 'Tân Kiểng',
                'name_en' => 'Tan Kieng',
                'full_name' => 'Phường Tân Kiểng',
                'full_name_en' => 'Tan Kieng Ward',
                'code_name' => 'tan_kieng',
                'district_code' => '766',
                'administrative_unit_id' => 1,
            ],
            [
                'code' => '26753',
                'name' => 'Tân Hưng',
                'name_en' => 'Tan Hung',
                'full_name' => 'Phường Tân Hưng',
                'full_name_en' => 'Tan Hung Ward',
                'code_name' => 'tan_hung',
                'district_code' => '766',
                'administrative_unit_id' => 1,
            ],
            [
                'code' => '26754',
                'name' => 'Bình Thuận',
                'name_en' => 'Binh Thuan',
                'full_name' => 'Phường Bình Thuận',
                'full_name_en' => 'Binh Thuan Ward',
                'code_name' => 'binh_thuan',
                'district_code' => '766',
                'administrative_unit_id' => 1,
            ],
            [
                'code' => '26755',
                'name' => 'Tân Quy',
                'name_en' => 'Tan Quy',
                'full_name' => 'Phường Tân Quy',
                'full_name_en' => 'Tan Quy Ward',
                'code_name' => 'tan_quy',
                'district_code' => '766',
                'administrative_unit_id' => 1,
            ],
            [
                'code' => '26756',
                'name' => 'Phú Thuận',
                'name_en' => 'Phu Thuan',
                'full_name' => 'Phường Phú Thuận',
                'full_name_en' => 'Phu Thuan Ward',
                'code_name' => 'phu_thuan',
                'district_code' => '766',
                'administrative_unit_id' => 1,
            ],
            [
                'code' => '26757',
                'name' => 'Nhà Bè',
                'name_en' => 'Nha Be',
                'full_name' => 'Phường Nhà Bè',
                'full_name_en' => 'Nha Be Ward',
                'code_name' => 'nha_be',
                'district_code' => '766',
                'administrative_unit_id' => 1,
            ],
            [
                'code' => '26758',
                'name' => 'Phú Mỹ',
                'name_en' => 'Phu My',
                'full_name' => 'Phường Phú Mỹ',
                'full_name_en' => 'Phu My Ward',
                'code_name' => 'phu_my',
                'district_code' => '766',
                'administrative_unit_id' => 1,
            ],
        ];

        // Insert dữ liệu
        foreach ($hcmDistricts as $district) {
            District::create($district);
        }

        foreach ($newWards as $ward) {
            Ward::create($ward);
        }

        $this->command->info('Đã thêm dữ liệu địa chỉ mở rộng!');
        $this->command->info('Quận/Huyện mới: ' . count($hcmDistricts));
        $this->command->info('Phường/Xã mới: ' . count($newWards));
    }
}
