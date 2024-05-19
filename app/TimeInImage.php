<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TimeInImage extends Model
{
    protected $table = 'timein_images';
    protected $fillable = [
        'time_in_id',
        'name',
        'url',
    ];
}
