<?php

namespace Modules\Customer\Models;

use App\Models\BaseModel;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class CustomerConsent extends BaseModel
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'customer_consents';

    protected $fillable = [
        'user_id',
        'consent_id',
        'has_consented',
        'consented_at',
        'revoked_at',
        'notes',
        'signature_data',
        'signature_file_path',
        'signed_at',
    ];

    protected $casts = [
        'user_id' => 'integer',
        'consent_id' => 'integer',
        'has_consented' => 'boolean',
        'consented_at' => 'datetime',
        'revoked_at' => 'datetime',
        'signed_at' => 'datetime',
    ];

    /**
     * Get the customer (user) that owns this consent
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the consent type
     */
    public function consent()
    {
        return $this->belongsTo(Consent::class, 'consent_id');
    }

    /**
     * Scope to get only consented records
     */
    public function scopeConsented($query)
    {
        return $query->where('has_consented', true);
    }

    /**
     * Scope to get only non-consented records
     */
    public function scopeNotConsented($query)
    {
        return $query->where('has_consented', false);
    }

    /**
     * Scope to get only active consents (not revoked)
     */
    public function scopeActive($query)
    {
        return $query->where('has_consented', true)->whereNull('revoked_at');
    }

    /**
     * Scope to get only revoked consents
     */
    public function scopeRevoked($query)
    {
        return $query->whereNotNull('revoked_at');
    }

    /**
     * Mark consent as given
     */
    public function giveConsent($notes = null)
    {
        $this->update([
            'has_consented' => true,
            'consented_at' => now(),
            'revoked_at' => null,
            'notes' => $notes,
        ]);
    }

    /**
     * Revoke consent
     */
    public function revokeConsent($notes = null)
    {
        $this->update([
            'has_consented' => false,
            'revoked_at' => now(),
            'notes' => $notes,
        ]);
    }

    /**
     * Add signature to consent
     */
    public function addSignature($signatureData, $signatureFilePath = null)
    {
        $this->update([
            'signature_data' => $signatureData,
            'signature_file_path' => $signatureFilePath,
            'signed_at' => now(),
        ]);
    }

    /**
     * Check if consent has signature
     */
    public function hasSignature()
    {
        return !is_null($this->signature_data) || !is_null($this->signature_file_path);
    }

    /**
     * Get signature image URL
     */
    public function getSignatureUrl()
    {
        if ($this->signature_file_path) {
            return asset('storage/' . $this->signature_file_path);
        }
        return null;
    }
}
