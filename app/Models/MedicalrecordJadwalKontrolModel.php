<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MedicalrecordJadwalKontrolModel extends Model
{
    use HasFactory;
    public $connection = "mysql11";
    protected $table = 'jadwal_kontrol';
}
