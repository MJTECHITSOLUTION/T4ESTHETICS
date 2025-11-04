<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Consultation extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'consultation_date',
        'patient_id',
        'items',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $casts = [
        'consultation_date' => 'date',
        'items' => 'array',
    ];

    /**
     * Get the user who created this consultation
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user who last updated this consultation
     */
    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Get the patient (user) for this consultation
     */
    public function patient()
    {
        return $this->belongsTo(User::class, 'patient_id');
    }
}

