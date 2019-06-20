<?php

namespace App\Entity;

class User
{
    public $id;
    public $firstname;
    public $lastname;
    public $email;

    /**
     * @param string $id
     * @param string $firstname
     * @param string $lastname
     * @param string $email
     */
    public function __construct($id, $firstname, $lastname, $email)
    {
        $this->id = $id;
        $this->firstname = $firstname;
        $this->lastname = $lastname;
        $this->email = $email;
    }
}
