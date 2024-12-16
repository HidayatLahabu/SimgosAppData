<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterStafModel extends Model
{
    use HasFactory;
    public $connection = "mysql2";
    protected $table = 'staf';
}
