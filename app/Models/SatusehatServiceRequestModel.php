<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SatusehatServiceRequestModel extends Model
{
    use HasFactory;
    public $connection = "mysql4";
    protected $table = 'service_request';
    protected $casts = [
        'id' => 'string',
    ];
}