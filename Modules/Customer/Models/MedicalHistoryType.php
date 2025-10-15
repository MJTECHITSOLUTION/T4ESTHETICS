<?php

namespace Modules\Customer\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class MedicalHistoryType extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'medical_history_types';

    protected $fillable = [
        'name',
        'created_by',
        'updated_by',
    ];
}


