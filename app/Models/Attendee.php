<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Attendee extends Model
{
    use SoftDeletes;

    public function bookings () {
        return $this->hasMany( Booking::class, 'attendee_id');
    }

    public function scopeAllColumnFilter( $q, $search ){
        if( !empty( $search ) ){
            $q->where( function( $whereClause ) use ( $search ) {
                $whereClause->where('name', 'LIKE', '%'.$search.'%')
                        ->orWhere('email', 'LIKE', '%'.$search.'%');
            });
        }

        return $q;
    }
}
