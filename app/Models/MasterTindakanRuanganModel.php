<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterTindakanRuanganModel extends Model
{
    use HasFactory;
    public $connection = "mysql2";
    protected $table = 'tindakan_ruangan';
}