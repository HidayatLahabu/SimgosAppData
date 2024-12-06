<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PendaftaranKonsulModel extends Model
{
    use HasFactory;
    public $connection = "mysql5";
    protected $table = 'konsul';
}
