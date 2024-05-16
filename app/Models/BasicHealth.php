<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BasicHealth extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'chronicIllness',
        'drugsDaily',
        'surgeryBefore',
        'cancerDiabetes',
        'examResult',
        'isMedicalOrder'
    ];
}
