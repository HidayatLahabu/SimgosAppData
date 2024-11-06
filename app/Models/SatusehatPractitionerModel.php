<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SatusehatPractitionerModel extends Model
{
    use HasFactory;
    public $connection = "mysql4";
    protected $table = 'practitioner';
    protected $casts = [
        'id' => 'string',
    ];
}