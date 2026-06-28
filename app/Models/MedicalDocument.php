<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MedicalDocument extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'patient_id',
        'uploaded_by',
        'file_path',
        'file_name',
        'mime_type',
        'size',
    ];
}
