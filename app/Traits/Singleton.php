<?php

/**
 * Singleton
 *
 * @since 1.0.0
 */

namespace Rhef\Traits;

trait Singleton
{
    /**
     * Store the singleton object.
     */
    private static $singleton = false;

    /**
     * Fetch an instance of the class.
     */
    public static function init()
    {
        if (self::$singleton === false) {
            self::$singleton = new self();
        }

        return self::$singleton;
    }

}
