<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Service;
use App\Models\Schedule;
use App\Models\User;
use App\Models\BasicHealth;

class Schedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'service_id',
        'patient_id',
        'doctor_id',
        'health_id',
        'booked_date',
        'start_time',
        'end_time',
        'link',
    ];


    public function health(): BelongsTo
    {
        return $this->belongsTo(BasicHealth::class, 'health_id');
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class, 'service_id');
    }

    public function doctor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }

    public function patient(): BelongsTo
    {
        return $this->belongsTo(User::class, 'patient_id');
    }


}
