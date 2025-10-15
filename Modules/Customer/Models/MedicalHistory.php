<?php

namespace Modules\Customer\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class MedicalHistory extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'medical_histories';

    protected $fillable = [
        'customer_id',
        'title',
        'type_id',
        'detail',
        'medication',
        'start_date',
        'end_date',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'customer_id' => 'integer',
        'type_id' => 'integer',
        'created_by' => 'integer',
        'updated_by' => 'integer',
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function type()
    {
        return $this->belongsTo(MedicalHistoryType::class, 'type_id');
    }
}


