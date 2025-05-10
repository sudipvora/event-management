<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Booking extends Model
{
    use SoftDeletes;

    public function eventData () {
        return $this->belongsTo( Event::class, 'event_id' );
    }

    public function attendee () {
        return $this->belongsTo( Attendee::class, 'attendee_id' );
    }
}
