<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PendaftaranAntrianModel extends Model
{
    use HasFactory;
    public $connection = "mysql5";
    protected $table = 'antrian_ruangan';
}
