<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MedicalrecordTandaVitalModel extends Model
{
    use HasFactory;
    public $connection = "mysql11";
    protected $table = 'tanda_vital';
}