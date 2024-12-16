<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogsAksesModel extends Model
{
    use HasFactory;
    public $connection = "mysql8";
    protected $table = 'pengguna_akses_log';
}
