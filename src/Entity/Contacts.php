<?php
namespace App\Entity;

use App\Helpers\Helpers;
use DateTime;

class Contacts
{

    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $email;

    /**
     * @var string
     */
    private $about;

    /**
     * @var string
     */
    private $message;

    /**
     * @var string
     */
    private $created_at;

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
     * Get the value of created_at
     */
    public function getCreatedAt()
    {
        return (new DateTime($this->created_at))->format('Y-m-d à H:m');
    }

    /**
     * Set the value of created_at
     *
     * @return  self
     */
    public function setCreatedAt($created_at)
    {
        $this->created_at = $created_at;
        return $this;
    }

    /**
     * Get the value of email
     *
     * @return  string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set the value of email
     *
     * @param  string  $email
     *
     * @return  self
     */
    public function setEmail(string $email)
    {
        $this->email = $email;

        return $this;
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
    public function setId($id)
    {
        $this->id = $id;

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
     * Get the value of name
     *
     * @return  string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set the value of name
     *
     * @param  string  $name
     *
     * @return  self
     */
    public function setName(string $name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get the value of message
     *
     * @return  string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Set the value of message
     *
     * @param  string  $message
     *
     * @return  self
     */
    public function setMessage(string $message)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * Get the value of about
     *
     * @return  string
     */
    public function getAbout()
    {
        return $this->about;
    }

    /**
     * Set the value of about
     *
     * @param  string  $about
     *
     * @return  self
     */
    public function setAbout(string $about)
    {
        $this->about = $about;

        return $this;
    }
}
