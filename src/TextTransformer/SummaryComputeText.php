<?php

namespace App\TextTransformer;

use App\Entity\Quote;

class SummaryComputeText implements ComputeText
{
    private $quoteReplacer;
    private $renderer;
    private $computeText;

    /**
     * @param QuoteReplacer $quoteReplacer
     * @param Renderer $renderer
     * @param ComputeText|null $computeText
     */
    public function __construct(
        QuoteReplacer $quoteReplacer,
        Renderer $renderer,
        ComputeText $computeText = null
    ) {
        $this->quoteReplacer = $quoteReplacer;
        $this->renderer = $renderer;
        $this->computeText = $computeText;
    }

    /**
     * @param string $initialText
     * @param array $data
     * @return string
     */
    public function compute($initialText, array $data)
    {
        if(isset($data['quote']) === false || ($data['quote'] instanceof Quote) === false) {
            return $initialText;
        }

        $quote = $data['quote'];

        $text = $this->quoteReplacer->replaceQuote(
            '[quote:summary]',
            $initialText,
            $this->renderer->renderText($quote->id)
        );

        return $this->computeText ? $this->computeText->compute($text, $data) : $text;
    }
}
