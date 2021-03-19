<?php
namespace App\Services;

use PDO;
use PDOException;

class Connection
{

    private $db = [
        'host' => 'localhost',
        'name' => 'slim',
        'user' => 'root',
        'pass' => '',
    ];

    public function __construct()
    {
    }

    public function getDb()
    {
        $host = $this->db['host'];
        $name = $this->db['name'];
        $user = $this->db['user'];
        $pass = $this->db['pass'];

        try {
            $db = new PDO("mysql:host={$host};dbname={$name};charset=utf8", $user, $pass);
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            dd("Connection failed : " . $e->getMessage());
        }

        return $db;
    }
}
