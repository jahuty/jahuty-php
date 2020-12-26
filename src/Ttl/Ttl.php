<?php

namespace Jahuty\Ttl;

/**
 * The time between when an item is cached and when it is considered stale.
 *
 * According to PSR-16, there are several valid time-to-live values:
 *
 *   a null value
 *     The implementing library may use a configured default duration, or
 *     lacking that, store the item for as long as possible.
 *
 *   a positive integer value
 *     The number of seconds to store the item.
 *
 *   a negative integer or zero value
 *     The item must be deleted from the cache if it exists, as it is expired
 *     already.
 *
 *   a DateInterval instance
 *     The DateInterval to store the item.
 */
class Ttl
{
    private $value;

    public function __construct($value)
    {
        if ($value !== null && !is_int($value) && !($value instanceof \DateInterval)) {
            throw new \InvalidArgumentException(
                "Ttl must be null, int, or DateInterval"
            );
        }

        $this->value = $value;
    }

    public function toSeconds(): ?int
    {
        if ($this->value === null) {
            $seconds = null;
        } elseif (is_int($this->value)) {
            $seconds = $this->value;
        } else {
            // The number of seconds in a DateInterval object depends on the
            // the current date and time.
            //
            // @see https://stackoverflow.com/a/25149337
            $start   = new \DateTimeImmutable();
            $end     = $start->add($this->value);
            $seconds = $end->getTimestamp() - $start->getTimestamp();
        }

        return $seconds;
    }
}
