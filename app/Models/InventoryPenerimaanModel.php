<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryPenerimaanModel extends Model
{
    use HasFactory;
    public $connection = "mysql3";
    protected $table = 'penerimaan';
}