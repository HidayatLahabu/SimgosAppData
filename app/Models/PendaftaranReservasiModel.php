<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PendaftaranReservasiModel extends Model
{
    use HasFactory;
    public $connection = "mysql5";
    protected $table = 'reservasi';
}