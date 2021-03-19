<?php
namespace App\Models;

use App\Entity\Projects;

class ProjectsModel extends Model
{

    protected $table = "projects";
    protected $class = Projects::class;
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
