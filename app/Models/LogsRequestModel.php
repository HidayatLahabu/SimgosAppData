<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogsRequestModel extends Model
{
    use HasFactory;
    public $connection = "mysql8";
    protected $table = 'pengguna_request_log';
}