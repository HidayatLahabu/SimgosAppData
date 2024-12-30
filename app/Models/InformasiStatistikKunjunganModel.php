<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class InformasiStatistikKunjunganModel extends Model
{
    use HasFactory;
    public $connection = "mysql12";
    protected $table = 'statistik_kunjungan';
}
