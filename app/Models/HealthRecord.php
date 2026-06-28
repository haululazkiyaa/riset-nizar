<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HealthRecord extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'patient_id',
        'doctor_id',
        'diagnosis',
        'notes',
        'metadata',
    ];

    protected function casts(): array
    {
        return [
            'metadata' => 'array',
        ];
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }
}
