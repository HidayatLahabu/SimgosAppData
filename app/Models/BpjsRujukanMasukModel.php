<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BpjsRujukanMasukModel extends Model
{
    use HasFactory;
    public $connection = "mysql6";
    protected $table = 'rujukan_masuk';
}