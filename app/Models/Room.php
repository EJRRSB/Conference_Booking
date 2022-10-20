<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Room extends Model
{
    use SoftDeletes;
    use HasFactory;

    protected $primaryKey = 'id';
    protected $table = 'rooms';

    protected $guarded = [
        'id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'added_by', 'id');
    }

    public function modified()
    {
        return $this->belongsTo(User::class, 'modified_by', 'id');
    }

    public function booking()
    {
        return $this->hasMany(Booking::class, 'id', 'booking_id');
    }
}
