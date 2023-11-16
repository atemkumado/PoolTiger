<?php

namespace App\Http\Controllers;

use App\Models\Province;
use App\Models\Talent;
use Illuminate\Http\Request;

class LeadController extends Controller
{
    const PROVINCE = ["An Giang","Bà rịa – Vũng tàu","Bắc Giang","Bắc Kạn","Bạc Liêu","Bắc Ninh","Bến Tre","Bình Định","Bình Dương","Bình Phước","Bình Thuận","Cà Mau","Cần Thơ","Cao Bằng","Đà Nẵng","Đắk Lắk","Đắk Nông","Điện Biên","Đồng Nai","Đồng Tháp","Gia Lai","Hà Giang","Hà Nam","Hà Nội","Hà Tĩnh","Hải Dương","Hải Phòng","Hậu Giang","Hòa Bình","Hưng Yên","Khánh Hòa","Kiên Giang","Kon Tum","Lai Châu","Lâm Đồng","Lạng Sơn","Lào Cai","Long An","Nam Định","Nghệ An","Ninh Bình","Ninh Thuận","Phú Thọ","Phú Yên","Quảng Bình","Quảng Nam","Quảng Ngãi","Quảng Ninh","Quảng Trị","Sóc Trăng","Sơn La","Tây Ninh","Thái Bình","Thái Nguyên","Thanh Hóa","Thừa Thiên Huế","Tiền Giang","Thành phố Hồ Chí Minh","Trà Vinh","Tuyên Quang","Vĩnh Long","Vĩnh Phúc","Yên Bái"];
    const ABILITY = ["PHP","Java","C#","JS","NodeJs","C++"];

    public function index()
    {
        // Return the view with the countries data

        // Todo: Get data from database table

        $filter = [
            'city' => self::PROVINCE,
            'ability' => self::ABILITY,
            'experience' => [''],
            'position' => [''],
            'english' => [''],
            'salary' => ['']
        ];
        return view('talents.filter', ['filter' => $filter]);
    }

    // Store the form data in the database
    public function getList(Request $request)
    {
        // Validate the request data
        // $request->validate([
        //     'name' => 'required|max:255',
        //     'email' => 'required|email|unique:users',
        //     'province' => 'required',
        // ]);

        // Create a new user instance
        $filter = [
             'city' => self::PROVINCE,
            'ability' => self::ABILITY,
            'experience' => [''],
            'position' => [''],
            'english' => [''],
            'salary' => ['']
        ];

        $input = new Talent();
        // Assign the request data to the user attributes
        $input->city = $request->city ?? '';
        $input->ability = $request->ability ?? '';
        $input->yoe = $request->yoe ?? '';
        $input->position = $request->position ?? '';
        $input->city = $request->city ?? '';
        $input->salary = $request->salary ?? '';
        // Save the user in the database


        // Redirect to the form view with a success message
        return view('talents.list', ['filter' => $filter, 'input' => $input]);
    }

    public function getProfile(){
        $province = Province::pluck('name')->toArray();
        return view('talents.profile');
    }
}
