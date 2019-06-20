<?php

namespace App;

use App\Entity\Quote;

class SummaryComputeText implements ComputeText
{
    private $quoteReplacer;
    private $renderer;
    private $computeText;

    public function __construct(
        QuoteReplacer $quoteReplacer,
        Renderer $renderer,
        ComputeText $computeText = null
    ) {
        $this->quoteReplacer = $quoteReplacer;
        $this->renderer = $renderer;
        $this->computeText = $computeText;
    }


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
