<?php

namespace App\Entity;

use App\ComputeText;

class Template
{
    public $id;
    public $subject;
    public $content;

    public function __construct($id, $subject, $content)
    {
        $this->id = $id;
        $this->subject = $subject;
        $this->content = $content;
    }

    public function setSubject(ComputeText $computeText, array $data)
    {
        $this->subject = $computeText->compute($this->subject, $data);
    }

    public function setContent(ComputeText $computeText, array $data)
    {
        $this->content = $computeText->compute($this->content, $data);
    }
}