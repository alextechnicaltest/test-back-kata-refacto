<?php

namespace App\Entity;

class Site
{
    public $id;
    public $url;

    /**
     * @param string $id
     * @param string $url
     */
    public function __construct($id, $url)
    {
        $this->id = $id;
        $this->url = $url;
    }
}
