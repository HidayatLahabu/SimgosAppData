<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SatusehatConditionHasilPaModel extends Model
{
    use HasFactory;
    public $connection = "mysql4";
    protected $table = 'condition_hasil_pa';
    protected $casts = [
        'id' => 'string',
    ];
}
