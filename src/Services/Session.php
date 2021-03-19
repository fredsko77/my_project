<?php
namespace App\Services;

class Session
{

    public function __construct()
    {
    }

    /**
     * @return array
     */
    public function all(): array
    {
        return $_SESSION;
    }

    /**
     * @param string $key
     * @param mixed $value
     * @return void
     */
    public function set(string $key, $value)
    {
        return $_SESSION[$key] = $value;
    }

    /**
     * @param string $key
     * @return void
     */
    function unset(string $key) {
        unset($_SESSION[$key]);
    }

    /**
     * @param string $key
     * @return mixed
     */
    public function get(string $key)
    {
        return array_key_exists($key, $_SESSION) ? $_SESSION[$key] : null;
    }

}
