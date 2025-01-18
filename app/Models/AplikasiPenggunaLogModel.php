<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AplikasiPenggunaLogModel extends Model
{
    use HasFactory;
    public $connection = "mysql9";
    protected $table = 'pengguna_log';
}