<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SatusehatCompositionModel extends Model
{
    use HasFactory;
    public $connection = "mysql4";
    protected $table = 'composition';
    protected $casts = [
        'id' => 'string',
    ];
}