<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SatusehatConditionPenilaianTumorModel extends Model
{
    use HasFactory;
    public $connection = "mysql4";
    protected $table = 'condition_penilaian_tumor';
    protected $casts = [
        'id' => 'string',
    ];
}