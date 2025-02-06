<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reports extends Model
{
    protected $table = 'reports';
    protected $fillable = [
        'user_id',
        'company_name',
        'location',
        'description_of_problem',
        'date_to_be_resolved',
        'date_reported',
        'date_resolved',
        'status',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function images()
    {
        return $this->hasMany(ReportImages::class);
    }

    public function getImagesAttribute()
    {
        return $this->images()->get();
    }

    public function getBeforeImagesAttribute()
    {
        return $this->images()->where('is_before', true)->get();
    }

    public function getAfterImagesAttribute()
    {
        return $this->images()->where('is_before', false)->get();
    }


}
