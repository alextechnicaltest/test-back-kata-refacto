<?php

namespace App;

use App\Entity\Template;

class TemplateManager
{
    private $subjectComputeText;
    private $contentComputeText;

    /**
     * @param ComputeText $subjectComputeText
     * @param ComputeText $contentComputeText
     */
    public function __construct(ComputeText $subjectComputeText, ComputeText $contentComputeText)
    {
        $this->subjectComputeText = $subjectComputeText;
        $this->contentComputeText = $contentComputeText;
    }

    /**
     * @param Template $tpl
     * @param array $data
     * @return Template
     */
    public function getTemplateComputed(Template $tpl, array $data)
    {
        if (!$tpl) {
            throw new \RuntimeException('no tpl given');
        }

        /** @var Template $replaced */
        $replaced = clone($tpl);
        $replaced->setSubject($this->subjectComputeText, $data);
        $replaced->setContent($this->contentComputeText, $data);

        return $replaced;
    }
}
