<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class InformasiPenunjangModel extends Model
{
    use HasFactory;
    public $connection = "mysql12";
    protected $table = 'penunjang';
}