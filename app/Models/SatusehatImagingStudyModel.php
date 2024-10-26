<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SatusehatImagingStudyModel extends Model
{
    use HasFactory;
    public $connection = "mysql4";
    protected $table = 'imaging_study';
    protected $casts = [
        'id' => 'string',
    ];
}
