<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SatusehatObservationModel extends Model
{
    use HasFactory;
    public $connection = "mysql4";
    protected $table = 'observation';
    protected $casts = [
        'id' => 'string',
    ];
}