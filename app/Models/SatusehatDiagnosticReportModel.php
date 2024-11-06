<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SatusehatDiagnosticReportModel extends Model
{
    use HasFactory;
    public $connection = "mysql4";
    protected $table = 'diagnostic_report';
    protected $casts = [
        'id' => 'string',
    ];
}