<?php

namespace Modules\Customer\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\User;
use Modules\Package\Models\Package;
use Modules\Customer\Models\DevisFacture;

class DevisUser extends BaseModel
{
    use HasFactory, SoftDeletes;

    protected $table = 'devis_user';

    protected $fillable = [
        'customer_id',
        'package_id',
        'devis_number',
        'subtotal',
        'tax_rate',
        'tax_amount',
        'total_amount',
        'remarks',
        'signature_path',
        'received_at',
        'accepted_at',
        'status',
        'valid_until',
        'created_by',
        'updated_by'
    ];

    protected $casts = [
        'customer_id' => 'integer',
        'package_id' => 'integer',
        'subtotal' => 'decimal:2',
        'tax_rate' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'valid_until' => 'datetime',
        'received_at' => 'datetime',
        'accepted_at' => 'datetime'
    ];

    const CUSTOM_FIELD_MODEL = 'Modules\Customer\Models\DevisUser';

    /**
     * Get the customer that owns the devis.
     */
    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    /**
     * Get the package associated with the devis.
     */
    public function package()
    {
        return $this->belongsTo(Package::class, 'package_id');
    }

    /**
     * Get the devis details for the devis.
     */
    public function devisDetails()
    {
        return $this->hasMany(DevisDetail::class, 'devis_user_id');
    }

    /**
     * Get the facture created from this devis (if any).
     */
    public function facture()
    {
        return $this->hasOne(DevisFacture::class, 'devis_user_id');
    }

    /**
     * Scope for active devis
     */
    public function scopeActive($query)
    {
        return $query->where('status', '!=', 'expired');
    }

    /**
     * Scope for draft devis
     */
    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }

    /**
     * Scope for sent devis
     */
    public function scopeSent($query)
    {
        return $query->where('status', 'sent');
    }

    /**
     * Generate unique devis number
     */
    public static function generateDevisNumber()
    {
        $prefix = 'DEV';
        $year = date('Y');
        $month = date('m');
        
        $lastDevis = self::where('devis_number', 'like', $prefix . $year . $month . '%')
            ->orderBy('devis_number', 'desc')
            ->first();
        
        if ($lastDevis) {
            $lastNumber = (int) substr($lastDevis->devis_number, -4);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }
        
        return $prefix . $year . $month . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Get formatted total amount
     */
    public function getFormattedTotalAttribute()
    {
        return '$' . number_format($this->total_amount, 2);
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
}
