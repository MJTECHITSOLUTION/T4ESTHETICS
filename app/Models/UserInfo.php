<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserInfo extends Model
{
    use HasFactory;

    protected $table = 'user_infos';

    protected $fillable = [
        'customer_id',
        'identite_nom',
        'identite_prenom',
        'identite_date_naissance',
        'identite_email',
        'identite_telephone',
        'identite_adresse',
        'identite_profession',
        'identite_newsletter',
        'antecedents',
        'autoimmunes',
        'autoimmunes_autre',
        'allergies',
        'allergies_autre',
        'traitements',
        'esthetiques',
        'esthetiques_autre',
        'motifs',
        'motifs_autre',
        'parrainage',
        'declaration_exactitude',
    ];

    protected $casts = [
        'identite_date_naissance' => 'date',
        'identite_newsletter' => 'boolean',
        'antecedents' => 'array',
        'autoimmunes' => 'array',
        'allergies' => 'array',
        'esthetiques' => 'array',
        'motifs' => 'array',
        'parrainage' => 'boolean',
        'declaration_exactitude' => 'boolean',
    ];
}


