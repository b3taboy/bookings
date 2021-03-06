<?php

declare(strict_types=1);

namespace Rinvex\Bookings\Models;

use Illuminate\Database\Eloquent\Model;
use Rinvex\Cacheable\CacheableEloquent;
use Rinvex\Support\Traits\ValidatingTrait;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Rinvex\Bookings\Contracts\BookingAvailabilityContract;

/**
 * Rinvex\Bookings\Models\BookingAvailability.
 *
 * @property int                                                $id
 * @property int                                                $bookable_id
 * @property string                                             $bookable_type
 * @property string                                             $day
 * @property \Carbon\Carbon                                     $starts_at
 * @property \Carbon\Carbon                                     $ends_at
 * @property float                                              $price
 * @property \Carbon\Carbon                                     $created_at
 * @property \Carbon\Carbon                                     $updated_at
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $bookable
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Bookings\Models\BookingAvailability whereBookableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Bookings\Models\BookingAvailability whereBookableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Bookings\Models\BookingAvailability whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Bookings\Models\BookingAvailability whereDay($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Bookings\Models\BookingAvailability whereEndsAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Bookings\Models\BookingAvailability whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Bookings\Models\BookingAvailability wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Bookings\Models\BookingAvailability whereStartsAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Bookings\Models\BookingAvailability whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class BookingAvailability extends Model implements BookingAvailabilityContract
{
    use ValidatingTrait;
    use CacheableEloquent;

    /**
     * {@inheritdoc}
     */
    protected $fillable = [
        'bookable_id',
        'bookable_type',
        'day',
        'starts_at',
        'ends_at',
        'price',
    ];

    /**
     * {@inheritdoc}
     */
    protected $casts = [
        'bookable_id' => 'integer',
        'bookable_type' => 'string',
        'day' => 'string',
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
        'price' => 'float',
    ];

    /**
     * {@inheritdoc}
     */
    protected $observables = [
        'validating',
        'validated',
    ];

    /**
     * The default rules that the model will validate against.
     *
     * @var array
     */
    protected $rules = [];

    /**
     * Whether the model should throw a
     * ValidationException if it fails validation.
     *
     * @var bool
     */
    protected $throwValidationExceptions = true;

    /**
     * Create a new Eloquent model instance.
     *
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->setTable(config('rinvex.bookings.tables.booking_availability'));
        $this->setRules([
            'bookable_id' => 'required|integer',
            'bookable_type' => 'required|string',
            'day' => 'in:sun,mon,tue,wed,thu,fri,sat',
            'starts_at' => 'nullable|time',
            'ends_at' => 'nullable|time',
            'price' => 'nullable|numeric',
        ]);
    }

    /**
     * Get the owning model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function bookable(): MorphTo
    {
        return $this->morphTo();
    }
}
