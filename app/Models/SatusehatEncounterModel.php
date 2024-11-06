<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SatusehatEncounterModel extends Model
{
    use HasFactory;
    public $connection = "mysql4";
    protected $table = 'encounter';
    protected $casts = [
        'id' => 'string',
    ];
}