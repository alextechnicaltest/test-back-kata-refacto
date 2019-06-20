<?php

namespace App\Entity;

class Destination
{
    public $id;
    public $countryName;
    public $conjunction;
    public $name;
    public $computerName;

    /**
     * @param string $id
     * @param string $countryName
     * @param string $conjunction
     * @param string $computerName
     */
    public function __construct($id, $countryName, $conjunction, $computerName)
    {
        $this->id = $id;
        $this->countryName = $countryName;
        $this->conjunction = $conjunction;
        $this->computerName = $computerName;
    }
}
