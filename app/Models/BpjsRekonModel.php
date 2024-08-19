<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BpjsRekonModel extends Model
{
    use HasFactory;
    public $connection = "mysql6";
    protected $table = 'rencana_kontrol';
}