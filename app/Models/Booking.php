<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Booking extends Model
{
    use SoftDeletes;
    use HasFactory;

    protected $primaryKey = 'id';
    protected $table = 'bookings';

    protected $guarded = [
        'id'
    ];


    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function room()
    {
        return $this->belongsTo(Room::class, 'room_id', 'id');
    }

    public function modified()
    {
        return $this->belongsTo(User::class, 'modified_by', 'id');
    }

    public function declined()
    {
        return $this->belongsTo(User::class, 'declined_by', 'id');
    }

    public function approved()
    {
        return $this->belongsTo(User::class, 'approved_by', 'id');
    }

    public function canceled()
    {
        return $this->belongsTo(User::class, 'canceled_by', 'id');
    }

    public function participants()
    {
        return $this->hasMany(Participants::class, 'booking_id', 'id');  
    }
}
