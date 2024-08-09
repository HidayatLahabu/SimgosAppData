<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SatusehatConditionModel extends Model
{
    use HasFactory;
    public $connection = "mysql4";
    protected $table = 'condition';
    protected $casts = [
        'id' => 'string',
    ];
}