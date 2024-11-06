<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SatusehatConsentModel extends Model
{
    use HasFactory;
    public $connection = "mysql4";
    protected $table = 'consent';
    protected $casts = [
        'id' => 'string',
    ];
}