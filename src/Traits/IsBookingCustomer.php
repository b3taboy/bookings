<?php

declare(strict_types=1);

namespace Rinvex\Bookings\Traits;

use Rinvex\Bookings\Models\Booking;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

trait IsBookingCustomer
{
    use IsBookingPerson;

    /**
     * The customer may have many bookings.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function bookings(): HasMany
    {
        return $this->hasMany(config('rinvex.bookings.models.booking'), 'customer_id', 'id');
    }

    /**
     * Book the given model for the given customer at the given dates with the given price.
     *
     * @param \Illuminate\Database\Eloquent\Model $bookable
     * @param string                              $starts
     * @param string                              $ends
     * @param float                               $price
     *
     * @return \Rinvex\Bookings\Models\Booking
     */
    public function newBooking(Model $bookable, string $starts, string $ends, float $price): Booking
    {
        return $this->bookings()->create([
            'bookable_id' => $bookable->getKey(),
            'bookable_type' => get_class($bookable),
            'customer_id' => $this->getKey(),
            'agent_id' => $this->getKey(),
            'starts_at' => $starts,
            'ends_at' => $ends,
            'price' => $price,
        ]);
    }
}
