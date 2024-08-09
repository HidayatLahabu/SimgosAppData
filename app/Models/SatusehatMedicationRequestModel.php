<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SatusehatMedicationRequestModel extends Model
{
    use HasFactory;
    public $connection = "mysql4";
    protected $table = 'medication_request';
    protected $casts = [
        'id' => 'string',
    ];
}