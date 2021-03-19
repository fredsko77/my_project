<?php
namespace App\Models;

use App\Entity\Contacts;

class ContactsModel extends Model
{

    protected $table = "contacts";
    protected $class = Contacts::class;
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

}
