<?php

namespace App\Entity;

class Quote
{
    public $id;
    public $siteId;
    public $destinationId;
    public $dateQuoted;

    /**
     * @param string $id
     * @param string $siteId
     * @param string $destinationId
     * @param string $dateQuoted
     */
    public function __construct($id, $siteId, $destinationId, $dateQuoted)
    {
        $this->id = $id;
        $this->siteId = $siteId;
        $this->destinationId = $destinationId;
        $this->dateQuoted = $dateQuoted;
    }
}