<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_number',
        'customer_name',
        'customer_email',
        'customer_phone',
        'shipping_address',
        'city',
        'district',
        'ward',
        'payment_method',
        'status',
        'subtotal',
        'shipping_fee',
        'tax',
        'total',
        'notes',
        'confirmed_at',
        'shipped_at',
        'delivered_at',
    ];

    protected $casts = [
        'confirmed_at' => 'datetime',
        'shipped_at' => 'datetime',
        'delivered_at' => 'datetime',
        'subtotal' => 'decimal:2',
        'shipping_fee' => 'decimal:2',
        'tax' => 'decimal:2',
        'total' => 'decimal:2',
    ];

    // Relationships
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Quan hệ với OrderItem
     */
    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    // Status labels
    public function getStatusLabelAttribute()
    {
        $statuses = [
            'pending' => 'Chờ xác nhận',
            'confirmed' => 'Đã xác nhận',
            'processing' => 'Đang xử lý',
            'shipping' => 'Đang vận chuyển',
            'delivered' => 'Đã giao hàng',
            'cancelled' => 'Đã hủy',
        ];

        return $statuses[$this->status] ?? $this->status;
    }

    // Status colors for UI
    public function getStatusColorAttribute()
    {
        $colors = [
            'pending' => 'warning',
            'confirmed' => 'info',
            'processing' => 'primary',
            'shipping' => 'purple',
            'delivered' => 'success',
            'cancelled' => 'danger',
        ];

        return $colors[$this->status] ?? 'secondary';
    }

    // Payment method labels
    public function getPaymentMethodLabelAttribute()
    {
        $methods = [
            'cod' => 'Thanh toán khi nhận hàng',
            'bank_transfer' => 'Chuyển khoản ngân hàng',
            'momo' => 'Ví MoMo',
        ];

        return $methods[$this->payment_method] ?? $this->payment_method;
    }    // Generate order number
    public static function generateOrderNumber()
    {
        $prefix = 'HT';
        $date = Carbon::now()->format('ymd');
        
        // Lấy order cuối cùng có order_number bắt đầu với prefix + date
        $lastOrder = self::where('order_number', 'like', $prefix . $date . '%')
                         ->orderBy('order_number', 'desc')
                         ->first();
        
        if ($lastOrder) {
            // Lấy 3 số cuối và tăng lên 1
            $lastSequence = (int)substr($lastOrder->order_number, -3);
            $sequence = $lastSequence + 1;
        } else {
            $sequence = 1;
        }
        
        return $prefix . $date . str_pad($sequence, 3, '0', STR_PAD_LEFT);
    }

    /**
     * Tính tổng số lượng items trong đơn hàng
     */
    public function getTotalItemsAttribute(): int
    {
        return $this->orderItems->sum('quantity');
    }

    /**
     * Kiểm tra có thể hủy đơn hàng không
     */
    public function canBeCancelled(): bool
    {
        return in_array($this->status, ['pending', 'confirmed']);
    }

    /**
     * Kiểm tra có thể xác nhận đơn hàng không
     */
    public function canBeConfirmed(): bool
    {
        return $this->status === 'pending';
    }

    /**
     * Kiểm tra có thể chuyển sang shipping không
     */
    public function canBeShipped(): bool
    {
        return in_array($this->status, ['confirmed', 'processing']);
    }

    /**
     * Kiểm tra có thể hoàn thành đơn hàng không
     */
    public function canBeDelivered(): bool
    {
        return $this->status === 'shipping';
    }

    // Formatted price accessors
    public function getFormattedSubtotalAttribute()
    {
        return number_format($this->subtotal, 0, ',', '.') . '₫';
    }

    public function getFormattedShippingFeeAttribute()
    {
        return number_format($this->shipping_fee, 0, ',', '.') . '₫';
    }

    public function getFormattedTotalAttribute()
    {
        return number_format($this->total, 0, ',', '.') . '₫';
    }
}
