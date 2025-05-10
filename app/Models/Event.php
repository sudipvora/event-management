<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Event extends Model
{
    use SoftDeletes;

    protected function casts(): array
    {
        return [
            'event_date' => 'date',
        ];
    }

    public function bookings(){
        return $this->hasMany( Booking::class, 'event_id');
    }

    public function scopeAllColumnFilter( $q, $search ){
        if( !empty( $search ) ){
            $q->where( function( $whereClause ) use ( $search ) {
                $whereClause->where('name', 'LIKE', '%'.$search.'%')
                        ->orWhere('description', 'LIKE', '%'.$search.'%');
            });
        }

        return $q;
    }

    public function isBookingFull(){
        $bookingCount = $this->bookings_count;
        if( is_null( $bookingCount ) ){
            $bookingCount = $this->bookings->count();
        }
        return $bookingCount == $this->capacity;
    }

    public function getRemainingSeats(){
        $bookingCount = $this->bookings_count;
        if( is_null( $bookingCount ) ){
            $bookingCount = $this->bookings->count();
        }
        return $this->capacity - $bookingCount;
    }
}
