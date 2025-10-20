<?php

namespace Modules\Customer\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Consent extends BaseModel
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'consents';

    protected $fillable = [
        'name',
        'description',
        'content',
        'is_active',
        'is_required',
        'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_required' => 'boolean',
        'sort_order' => 'integer',
    ];

    /**
     * Get the customer consents for this consent type
     */
    public function customerConsents()
    {
        return $this->hasMany(CustomerConsent::class, 'consent_id');
    }

    /**
     * Scope to get only active consents
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to get only required consents
     */
    public function scopeRequired($query)
    {
        return $query->where('is_required', true);
    }

    /**
     * Scope to order by sort_order
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('name');
    }
}
