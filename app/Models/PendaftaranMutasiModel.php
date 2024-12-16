<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PendaftaranMutasiModel extends Model
{
    use HasFactory;
    public $connection = "mysql5";
    protected $table = 'mutasi';
}
