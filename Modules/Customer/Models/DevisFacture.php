<?php

namespace Modules\Customer\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DevisFacture extends BaseModel
{
    use HasFactory;

    protected $table = 'devis_factures';

    protected $fillable = [
        'devis_user_id',
        'facture_number',
        'subtotal',
        'tax_amount',
        'total_amount',
        'status',
        'issued_at',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'devis_user_id' => 'integer',
        'subtotal' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'issued_at' => 'datetime',
    ];

    public function devis()
    {
        return $this->belongsTo(DevisUser::class, 'devis_user_id');
    }
}



