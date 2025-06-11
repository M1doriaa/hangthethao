<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItem extends Model
{
    protected $fillable = [
        'order_id',
        'product_id',
        'product_name',
        'product_image',
        'size',
        'color',
        'price',
        'quantity',
        'total'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'total' => 'decimal:2',
        'quantity' => 'integer'
    ];

    /**
     * Quan hệ với Order
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Quan hệ với Product
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Định dạng giá tiền
     */
    public function getFormattedPriceAttribute(): string
    {
        return number_format($this->price, 0, ',', '.') . ' đ';
    }

    /**
     * Định dạng tổng tiền
     */
    public function getFormattedTotalAttribute(): string
    {
        return number_format($this->total, 0, ',', '.') . ' đ';
    }

    /**
     * Tính toán tổng tiền khi thay đổi quantity hoặc price
     */
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($orderItem) {
            $orderItem->total = $orderItem->price * $orderItem->quantity;
        });
    }
}
