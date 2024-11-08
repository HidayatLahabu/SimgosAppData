<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SatusehatLoincTerminologiModel extends Model
{
    use HasFactory;
    public $connection = "mysql4";
    protected $table = 'loinc_terminologi';
}