<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Carbon\Carbon;

class OrderController extends Controller
{
    /**
     * Hiển thị danh sách đơn hàng
     */
    public function index(Request $request)
    {
        $query = Order::with('orderItems');

        // Tìm kiếm theo mã đơn hàng hoặc tên khách hàng
        if ($request->has('search') && $request->search) {
            $query->where(function($q) use ($request) {
                $q->where('order_number', 'like', '%' . $request->search . '%')
                  ->orWhere('customer_name', 'like', '%' . $request->search . '%')
                  ->orWhere('customer_email', 'like', '%' . $request->search . '%')
                  ->orWhere('customer_phone', 'like', '%' . $request->search . '%');
            });
        }

        // Lọc theo trạng thái
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        // Lọc theo phương thức thanh toán
        if ($request->has('payment_method') && $request->payment_method) {
            $query->where('payment_method', $request->payment_method);
        }

        // Lọc theo ngày
        if ($request->has('date_from') && $request->date_from) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->has('date_to') && $request->date_to) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $orders = $query->orderBy('created_at', 'desc')->paginate(20);

        // Thống kê
        $stats = [
            'total_orders' => Order::count(),
            'pending_orders' => Order::where('status', 'pending')->count(),
            'confirmed_orders' => Order::where('status', 'confirmed')->count(),
            'shipping_orders' => Order::where('status', 'shipping')->count(),
            'delivered_orders' => Order::where('status', 'delivered')->count(),
            'cancelled_orders' => Order::where('status', 'cancelled')->count(),
            'total_revenue' => Order::where('status', 'delivered')->sum('total'),
            'today_orders' => Order::whereDate('created_at', Carbon::today())->count(),
        ];

        return view('admin.orders.index', compact('orders', 'stats'));
    }

    /**
     * Hiển thị chi tiết đơn hàng
     */
    public function show($id)
    {
        $order = Order::with('orderItems.product')->findOrFail($id);
        return view('admin.orders.show', compact('order'));
    }

    /**
     * Cập nhật trạng thái đơn hàng
     */
    public function update(Request $request, $id)
    {
        $order = Order::findOrFail($id);
        
        $validated = $request->validate([
            'status' => 'required|in:pending,confirmed,processing,shipping,delivered,cancelled',
            'notes' => 'nullable|string|max:1000'
        ]);

        $oldStatus = $order->status;
        $newStatus = $validated['status'];

        // Kiểm tra logic chuyển trạng thái
        if (!$this->canUpdateStatus($oldStatus, $newStatus)) {
            return redirect()->back()->with('error', 'Không thể chuyển từ trạng thái "' . $order->status_label . '" sang "' . $this->getStatusLabel($newStatus) . '"');
        }

        // Cập nhật timestamp tương ứng
        switch ($newStatus) {
            case 'confirmed':
                $order->confirmed_at = Carbon::now();
                break;
            case 'shipping':
                $order->shipped_at = Carbon::now();
                break;
            case 'delivered':
                $order->delivered_at = Carbon::now();
                break;
        }

        $order->status = $newStatus;
        $order->save();

        // Log thay đổi trạng thái (có thể mở rộng sau)
        \Log::info("Order {$order->order_number} status changed from {$oldStatus} to {$newStatus}");

        return redirect()->back()->with('success', 'Cập nhật trạng thái đơn hàng thành công!');
    }

    /**
     * Kiểm tra có thể chuyển trạng thái không
     */
    private function canUpdateStatus($oldStatus, $newStatus)
    {
        // Logic chuyển trạng thái hợp lệ
        $allowedTransitions = [
            'pending' => ['confirmed', 'cancelled'],
            'confirmed' => ['processing', 'shipping', 'cancelled'],
            'processing' => ['shipping', 'cancelled'],
            'shipping' => ['delivered', 'cancelled'],
            'delivered' => [], // Không thể chuyển từ delivered
            'cancelled' => [], // Không thể chuyển từ cancelled
        ];

        return in_array($newStatus, $allowedTransitions[$oldStatus] ?? []);
    }

    /**
     * Lấy label của trạng thái
     */
    private function getStatusLabel($status)
    {
        $statuses = [
            'pending' => 'Chờ xác nhận',
            'confirmed' => 'Đã xác nhận',
            'processing' => 'Đang xử lý',
            'shipping' => 'Đang vận chuyển',
            'delivered' => 'Đã giao hàng',
            'cancelled' => 'Đã hủy',
        ];

        return $statuses[$status] ?? $status;
    }

    /**
     * API endpoint để cập nhật nhanh trạng thái
     */
    public function updateStatus(Request $request, $id)
    {
        $order = Order::findOrFail($id);
        
        $validated = $request->validate([
            'status' => 'required|in:pending,confirmed,processing,shipping,delivered,cancelled'
        ]);

        $oldStatus = $order->status;
        $newStatus = $validated['status'];

        if (!$this->canUpdateStatus($oldStatus, $newStatus)) {
            return response()->json([
                'success' => false,
                'message' => 'Không thể chuyển trạng thái này'
            ], 400);
        }

        // Cập nhật timestamp
        switch ($newStatus) {
            case 'confirmed':
                $order->confirmed_at = Carbon::now();
                break;
            case 'shipping':
                $order->shipped_at = Carbon::now();
                break;
            case 'delivered':
                $order->delivered_at = Carbon::now();
                break;
        }

        $order->status = $newStatus;
        $order->save();

        return response()->json([
            'success' => true,
            'message' => 'Cập nhật trạng thái thành công',
            'status_label' => $order->status_label,
            'status_color' => $order->status_color
        ]);
    }

    /**
     * Xóa đơn hàng (chỉ với trạng thái pending hoặc cancelled)
     */
    public function destroy($id)
    {
        $order = Order::findOrFail($id);
        
        // Chỉ cho phép xóa đơn hàng pending hoặc cancelled
        if (!in_array($order->status, ['pending', 'cancelled'])) {
            return redirect()->back()->with('error', 'Chỉ có thể xóa đơn hàng ở trạng thái "Chờ xác nhận" hoặc "Đã hủy"');
        }

        $order->delete();

        return redirect()->route('admin.orders.index')->with('success', 'Xóa đơn hàng thành công!');
    }
}
