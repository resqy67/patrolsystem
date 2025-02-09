<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ReportImages extends Model
{
    use HasFactory;
    protected $appends = ['url'];

    protected $fillable = [
        'reports_id',
        'image_path',
        'is_before', // true if the image is before the repair, false if after
    ];

    // use url attribute to get the full path of the image
    public function getUrlAttribute()
    {
        return asset('storage/' . $this->image_path);
    }

    public function report()
    {
        return $this->belongsTo(Reports::class);
    }
}
