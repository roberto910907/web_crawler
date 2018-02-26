<?php
/**
 * @author  Roberto Rielo <roberto910907@gmail.com>.
 *
 * @version Crawler v1.0 08/02/18 12:04 PM
 */

namespace App\Session;

use App\Session\Interfaces\SessionInterface;

class Session implements SessionInterface
{
    /**
     * {@inheritdoc}
     */
    public function start()
    {
        session_start();
    }

    /**
     * {@inheritdoc}
     */
    public function has($name)
    {
        return array_key_exists($name, $_SESSION);
    }

    /**
     * {@inheritdoc}
     */
    public function get($name, $default = null)
    {
        if ($this->has($name)) {
            return $_SESSION[$name];
        }

        if ($default) {
            return $default;
        }

        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function set($name, $value)
    {
        $_SESSION[$name] = $value;
    }

    /**
     * {@inheritdoc}
     */
    public function remove($name)
    {
        unset($_SESSION[$name]);
    }
}
