<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SatusehatBarangToBzaModel extends Model
{
    use HasFactory;
    public $connection = "mysql4";
    protected $table = 'barang_to_bza';
}
