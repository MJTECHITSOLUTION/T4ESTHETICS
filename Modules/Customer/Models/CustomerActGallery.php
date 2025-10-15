<?php

namespace Modules\Customer\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\MediaLibrary\HasMedia;

class CustomerActGallery extends BaseModel implements HasMedia
{
    use HasFactory;

    protected $table = 'customer_act_galleries';

    protected $fillable = [
        'customer_act_id',
        'phase',
        'session_date',
        'note',
    ];

    protected $casts = [
        'customer_act_id' => 'integer',
        'session_date' => 'datetime',
    ];

    public function act()
    {
        return $this->belongsTo(CustomerAct::class, 'customer_act_id');
    }
}


