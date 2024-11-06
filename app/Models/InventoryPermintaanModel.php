<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryPermintaanModel extends Model
{
    use HasFactory;
    public $connection = "mysql3";
    protected $table = 'permintaan';
}