<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SatusehatCarePlanModel extends Model
{
    use HasFactory;
    public $connection = "mysql4";
    protected $table = 'care_plan';
    protected $casts = [
        'id' => 'string',
    ];
}
