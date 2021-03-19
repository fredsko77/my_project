<?php
namespace App\Models;

use App\Entity\Users;

class UsersModel extends Model
{

    protected $table = "users";
    protected $class = Users::class;
    protected $db;

    public function __construct()
    {
        $this->db = parent::getDb();
    }

    /**
     * @return an instance of db
     */
    public function getDb()
    {
        return parent::getDb();
    }

    /**
     * @param string $email
     *
     * @return boolean
     */
    public function mailExists(string $email): bool
    {
        $sql = "SELECT * FROM {$this->table} WHERE email = :email";
        $stmt = $this->getDb()->prepare($sql);
        $stmt->execute([':email' => $email]);

        if ($stmt->rowCount() > 0) {

            return true;
        }

        return false;
    }

    /**
     * @param string $email
     *
     * @return mixed
     */
    public function getUserEmail(string $email, string $class = '')
    {
        $sql = "SELECT * FROM {$this->table} WHERE email = :email";
        $stmt = $this->getDb()->prepare($sql);
        $stmt->execute([':email' => $email]);

        if ($stmt->rowCount() > 0) {

            return $class === '' ? $stmt->fetch() : new $class($stmt->fetch());
        }

        return false;
    }

    /**
     * @param string $token
     *
     * @return mixed
     */
    public function checkToken(string $token, string $class = '')
    {
        $sql = "SELECT * FROM {$this->table} WHERE token = :token";
        $stmt = $this->getDb()->prepare($sql);
        $stmt->execute([':token' => $token]);
        
        if ($stmt->rowCount() > 0) {

            return $class === '' ? $stmt->fetch() : new $class($stmt->fetch());
        }

        return false;
    }

}
