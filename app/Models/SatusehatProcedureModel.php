<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SatusehatProcedureModel extends Model
{
    use HasFactory;
    public $connection = "mysql4";
    protected $table = 'procedure';
    protected $casts = [
        'id' => 'string',
    ];
}