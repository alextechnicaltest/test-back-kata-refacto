<?php

namespace App\Entity;

use App\TextTransformer\ComputeText;

class Template
{
    public $id;
    public $subject;
    public $content;

    /**
     * @param string $id
     * @param string $subject
     * @param string $content
     */
    public function __construct($id, $subject, $content)
    {
        $this->id = $id;
        $this->subject = $subject;
        $this->content = $content;
    }

    /**
     * @param ComputeText $computeText
     * @param array $data
     */
    public function setSubject(ComputeText $computeText, array $data)
    {
        $this->subject = $computeText->compute($this->subject, $data);
    }

    /**
     * @param ComputeText $computeText
     * @param array $data
     */
    public function setContent(ComputeText $computeText, array $data)
    {
        $this->content = $computeText->compute($this->content, $data);
    }
}