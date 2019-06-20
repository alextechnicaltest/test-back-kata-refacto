<?php

namespace App\TextTransformer;

use App\Entity\Quote;
use App\Repository\DestinationRepository;
use App\Repository\SiteRepository;

class DestinationLinkComputeText implements ComputeText
{
    private $quoteReplacer;
    private $destinationRepository;
    private $siteRepository;
    private $computeText;

    /**
     * @param QuoteReplacer $quoteReplacer
     * @param DestinationRepository $destinationRepository
     * @param SiteRepository $siteRepository
     * @param ComputeText|null $computeText
     */
    public function __construct(
        QuoteReplacer $quoteReplacer,
        DestinationRepository $destinationRepository,
        SiteRepository $siteRepository,
        ComputeText $computeText = null
    ) {
        $this->quoteReplacer = $quoteReplacer;
        $this->destinationRepository = $destinationRepository;
        $this->computeText = $computeText;
        $this->siteRepository = $siteRepository;
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

        /** @var Quote $quote */
        $quote = $data['quote'];

        $destination = $this->destinationRepository->getById($quote->destinationId);
        $site = $this->siteRepository->getById($quote->siteId);

        $text = $this->quoteReplacer->replaceQuote(
            '[quote:destination_link]',
            $initialText,
            $site->url . '/' . $destination->countryName . '/quote/' . $quote->id,
            ''
        );

        return $this->computeText ? $this->computeText->compute($text, $data) : $text;
    }
}