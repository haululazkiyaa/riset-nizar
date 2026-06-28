<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'created_by',
        'medical_record_number',
        'name',
        'date_of_birth',
        'gender',
        'address',
        'phone',
    ];

    protected function casts(): array
    {
        return [
            'date_of_birth' => 'date',
        ];
    }

    public function healthRecords()
    {
        return $this->hasMany(HealthRecord::class);
    }

    public function vitalSigns()
    {
        return $this->hasMany(VitalSign::class);
    }
}
