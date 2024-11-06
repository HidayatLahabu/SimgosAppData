<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SatusehatPatientModel extends Model
{
    use HasFactory;
    public $connection = "mysql4";
    protected $table = 'patient';
    protected $casts = [
        'id' => 'string',
    ];
}