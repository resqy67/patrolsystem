<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ReportImages extends Model
{
    use HasFactory;

    protected $fillable = [
        'report_id',
        'image_path',
        'is_before',
    ];

    public function report()
    {
        return $this->belongsTo(Reports::class);
    }
}
