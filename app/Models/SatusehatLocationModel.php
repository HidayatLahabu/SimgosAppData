<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SatusehatLocationModel extends Model
{
    use HasFactory;
    public $connection = "mysql4";
    protected $table = 'location';
    protected $casts = [
        'id' => 'string',
    ];
}