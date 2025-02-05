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


}
