<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PageController extends Controller
{
    /**
     * Hiển thị trang giới thiệu
     */
    public function about()
    {
        return view('pages.about');
    }

    /**
     * Hiển thị trang liên hệ
     */
    public function contact()
    {
        return view('pages.contact');
    }

    /**
     * Xử lý form liên hệ
     */
    public function contactSubmit(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:1000',
        ]);

        // Trong thực tế, bạn có thể lưu vào database hoặc gửi email
        // Ở đây chỉ redirect với thông báo thành công
        return redirect()->route('contact')->with('success', 'Cám ơn bạn đã liên hệ! Chúng tôi sẽ phản hồi sớm nhất có thể.');
    }
}
