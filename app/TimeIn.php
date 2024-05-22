<?php

namespace App;

use App\User;
use Illuminate\Database\Eloquent\Model;

class TimeIn extends Model
{
    protected $table = 'time_ins';
    protected $fillable = [
        'user_id',
        'time_in',
        'time_out',
        'date',
        'longitude_in',
        'longitude_out',
        'latitude_in',
        'latitude_out',
        'place_in',
        'place_out',
        'remark',
    ];


    public function getTimeInFormattedAttribute()
    {
        return $this->time_in->format('Y-m-d H:i:s');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->where('time_in', 'like', '%' . $search . '%');
        });
    }

    public function images()
    {
        return $this->hasMany(TimeInImage::class, 'time_in_id', 'id');
    }
}
