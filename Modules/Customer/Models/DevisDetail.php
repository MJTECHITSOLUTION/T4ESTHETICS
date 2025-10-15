<?php

namespace Modules\Customer\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Service\Models\Service;

class DevisDetail extends BaseModel
{
    use HasFactory;

    protected $table = 'devis_details';

    protected $fillable = [
        'devis_user_id',
        'service_name',
        'service_id',
        'quantity',
        'price',
        'discount',
        'subtotal',
        'tax_amount',
        'total',
        'remarks',
        'number_of_lot',
        'created_by',
        'updated_by'
    ];

    protected $casts = [
        'devis_user_id' => 'integer',
        'service_id' => 'integer',
        'quantity' => 'integer',
        'price' => 'decimal:2',
        'discount' => 'decimal:2',
        'subtotal' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'total' => 'decimal:2'
    ];

    const CUSTOM_FIELD_MODEL = 'Modules\Customer\Models\DevisDetail';

    /**
     * Get the devis user that owns the detail.
     */
    public function devisUser()
    {
        return $this->belongsTo(DevisUser::class, 'devis_user_id');
    }

    /**
     * Get the service associated with the detail.
     */
    public function service()
    {
        return $this->belongsTo(Service::class, 'service_id');
    }

    /**
     * Get formatted price
     */
    public function getFormattedPriceAttribute()
    {
        return '$' . number_format($this->price, 2);
    }

    /**
     * Get formatted discount
     */
    public function getFormattedDiscountAttribute()
    {
        return '$' . number_format($this->discount, 2);
    }

    /**
     * Get formatted subtotal
     */
    public function getFormattedSubtotalAttribute()
    {
        return '$' . number_format($this->subtotal, 2);
    }

    /**
     * Get formatted tax amount
     */
    public function getFormattedTaxAmountAttribute()
    {
        return '$' . number_format($this->tax_amount, 2);
    }

    /**
     * Get formatted total
     */
    public function getFormattedTotalAttribute()
    {
        return '$' . number_format($this->total, 2);
    }

    /**
     * Calculate subtotal based on quantity, price and discount
     */
    public function calculateSubtotal()
    {
        return $this->quantity * ($this->price - $this->discount);
    }

    /**
     * Calculate tax amount based on subtotal and tax rate
     */
    public function calculateTaxAmount($taxRate = 0)
    {
        return ($this->calculateSubtotal() * $taxRate) / 100;
    }

    /**
     * Calculate total including tax
     */
    public function calculateTotal($taxRate = 0)
    {
        $subtotal = $this->calculateSubtotal();
        $taxAmount = $this->calculateTaxAmount($taxRate);
        return $subtotal + $taxAmount;
    }
}
