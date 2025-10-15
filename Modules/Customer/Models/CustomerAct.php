<?php

namespace Modules\Customer\Models;

use App\Models\BaseModel;
use App\Models\Branch;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Service\Models\Service;

class CustomerAct extends BaseModel
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'customer_acts';

    protected $fillable = [
        'user_id',
        'service_id',
        'branch_id',
        'employee_id',
        'act_date',
        'status',
        'note',
        'service_price',
        'duration_min',
    ];

    protected $casts = [
        'user_id' => 'integer',
        'service_id' => 'integer',
        'branch_id' => 'integer',
        'employee_id' => 'integer',
        'service_price' => 'double',
        'duration_min' => 'integer',
        'act_date' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function service()
    {
        return $this->belongsTo(Service::class, 'service_id');
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    public function employee()
    {
        return $this->belongsTo(User::class, 'employee_id');
    }

    public function galleries()
    {
        return $this->hasMany(CustomerActGallery::class, 'customer_act_id');
    }
}


