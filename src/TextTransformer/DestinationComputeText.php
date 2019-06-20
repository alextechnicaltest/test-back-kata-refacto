<?php

namespace App\TextTransformer;

use App\Entity\Quote;
use App\Repository\DestinationRepository;

class DestinationComputeText implements ComputeText
{
    private $quoteReplacer;
    private $destinationRepository;
    private $computeText;

    /**
     * @param QuoteReplacer $quoteReplacer
     * @param DestinationRepository $destinationRepository
     * @param ComputeText|null $computeText
     */
    public function __construct(
        QuoteReplacer $quoteReplacer,
        DestinationRepository $destinationRepository,
        ComputeText $computeText = null
    ) {
        $this->quoteReplacer = $quoteReplacer;
        $this->destinationRepository = $destinationRepository;
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

        $destination = $this->destinationRepository->getById($quote->destinationId);

        $text = $this->quoteReplacer->replaceQuote(
            '[quote:destination_name]',
            $initialText,
            $destination->countryName
        );

        return $this->computeText ? $this->computeText->compute($text, $data) : $text;
    }
}