<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryTransaksiModel extends Model
{
    use HasFactory;
    public $connection = "mysql3";
    protected $table = 'transaksi_stok_ruangan';
}