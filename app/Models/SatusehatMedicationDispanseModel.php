<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SatusehatMedicationDispanseModel extends Model
{
    use HasFactory;
    public $connection = "mysql4";
    protected $table = 'medication_dispanse';
    protected $casts = [
        'id' => 'string',
    ];
}