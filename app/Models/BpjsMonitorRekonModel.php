<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BpjsMonitorRekonModel extends Model
{
    use HasFactory;
    public $connection = "mysql6";
    protected $table = 'monitoring_rencana_kontrol';
}