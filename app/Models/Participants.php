<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Participants extends Model
{
    use SoftDeletes;
    use HasFactory; 

    
    protected $primaryKey = 'id';
    protected $table = 'participants';

    protected $guarded = [
        'id'
    ];



    
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    
    public function booking()
    {
        return $this->belongsTo(Booking::class, 'booking_id', 'id');
    }
}
