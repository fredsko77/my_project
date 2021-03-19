<?php
namespace App\Entity;

use App\Helpers\Helpers;

class Projects
{

    /**
     * @var int $id
     */
    private $id;

    /**
     * @var string $name
     */
    private $name;

    /**
     * @var string $link
     */
    private $link;

    /**
     * @var string $details
     */
    private $details;

    /**
     * @var string $techno
     */
    private $tasks;

    /**
     * @var string $techno
     */
    private $techno;

    /**
     * @var string $image
     */
    private $image;

    /**
     * @var string $status
     */
    private $status;

    const TECHNOS = [
        'seo' => 'Seo',
        'bootstrap' => 'Bootstrap',
        'js' => 'Javascript',
        'jquery' => 'jQuery',
        'ajax' => 'Ajax',
        'vue' => 'VueJs',
        'php' => 'PHP',
        'mysql' => 'MySQL',
        'python' => 'Python',
        'slim' => 'Slim',
        'symfony' => 'Symfony',
        'wordpress' => 'Wordpress',
    ];

    public function __construct($data = [])
    {
        $this->_hydrate($data);
    }

    /**
     * _hydrate
     *
     * @param  mixed $data
     *
     * @return void
     */
    public function _hydrate(array $data)
    {
        foreach ($data as $k => $d):
            $method = 'set' . Helpers::getMethod($k);
            method_exists($this, $method) ? $this->$method($d) : null;
        endforeach;
    }

    /**
     * Get the value of id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set the value of id
     *
     * @return  self
     */
    public function setId(int $id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get $name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set $name
     *
     * @param string $name
     *
     * @return self
     */
    public function setName(string $name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get $link
     *
     * @return string $link
     */
    public function getLink()
    {
        return $this->link;
    }

    /**
     * Set $link
     *
     * @param string $link
     *
     * @return  self
     */
    public function setLink(string $link)
    {
        $this->link = $link;

        return $this;
    }

    /**
     * Get $details
     *
     * @return  string
     */
    public function getDetails()
    {
        return $this->details;
    }

    /**
     * Set $details
     *
     * @param  string $details
     *
     * @return  self
     */
    public function setDetails(string $details)
    {
        $this->details = $details;

        return $this;
    }

    /**
     * Get $techno
     *
     * @return  int
     */
    public function getTechno()
    {
        return $this->techno;
    }

    /**
     * Set $techno
     *
     * @param string $techno
     *
     * @return  self
     */
    public function setTechno(string $techno)
    {
        $this->techno = unserialize($techno);

        return $this;
    }

    /**
     * Get $image
     *
     * @return string
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Set $image
     *
     * @param string $image
     *
     * @return  self
     */
    public function setImage(string $image)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Get $status
     *
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set $status
     *
     * @param string $status
     *
     * @return  self
     */
    public function setStatus(string $status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return array
     */
    public function serialize(): array
    {
        $properties = Helpers::getProperties(self::class);

        $json_encode = [];

        foreach ($properties as $property) {
            $method = 'get' . Helpers::getMethod($property);
            if (method_exists($this, $method)) {
                $json_encode[$property] = $this->$method();
            }
        }

        return $json_encode;
    }

    /**
     * Get $tasks
     *
     * @return  string
     */
    public function getTasks()
    {
        return $this->tasks;
    }

    /**
     * Set $tasks
     *
     * @param  string  $tasks
     *
     * @return  self
     */
    public function setTasks(string $tasks)
    {
        $this->tasks = unserialize($tasks);

        return $this;
    }
}
