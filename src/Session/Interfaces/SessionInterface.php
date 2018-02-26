<?php
/**
 * @author  Roberto Rielo <roberto910907@gmail.com>.
 *
 * @version Crawler v1.0 08/02/18 12:02 PM
 */

namespace App\Session\Interfaces;

interface SessionInterface
{
    /**
     * Starts the session storage.
     *
     * @throws \RuntimeException if session fails to start
     *
     * @return bool True if session started
     */
    public function start();

    /**
     * Checks if an attribute is defined.
     *
     * @param string $name The attribute name
     *
     * @return bool true if the attribute is defined, false otherwise
     */
    public function has($name);

    /**
     * Returns an attribute.
     *
     * @param string $name    The attribute name
     * @param mixed  $default The default value if not found
     *
     * @return mixed
     */
    public function get($name, $default = null);

    /**
     * Sets an attribute.
     *
     * @param string $name
     * @param mixed  $value
     */
    public function set($name, $value);

    /**
     * Removes an attribute.
     *
     * @param string $name
     *
     * @return mixed The removed value or null when it does not exist
     */
    public function remove($name);
}
