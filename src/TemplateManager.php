<?php

namespace App;

use App\Entity\Template;
use App\Context\ApplicationContext;
use App\Entity\User;
use App\Repository\QuoteRepository;
use App\Repository\SiteRepository;
use App\Repository\DestinationRepository;
use App\Entity\Quote;

class TemplateManager
{
    private $quoteRepository;
    private $siteRepository;
    private $destinationRepository;
    private $applicationContext;
    private $renderer;

    /**
     * @param QuoteRepository $quoteRepository
     * @param SiteRepository $siteRepository
     * @param DestinationRepository $destinationRepository
     * @param ApplicationContext $applicationContext
     * @param Renderer $renderer
     */
    public function __construct(
        QuoteRepository $quoteRepository,
        SiteRepository $siteRepository,
        DestinationRepository $destinationRepository,
        ApplicationContext $applicationContext,
        Renderer $renderer
    ) {
        $this->quoteRepository = $quoteRepository;
        $this->siteRepository = $siteRepository;
        $this->destinationRepository = $destinationRepository;
        $this->applicationContext = $applicationContext;
        $this->renderer = $renderer;
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

        $replaced = clone($tpl);
        $replaced->subject = $this->computeText($replaced->subject, $data);
        $replaced->content = $this->computeText($replaced->content, $data);

        return $replaced;
    }

    /**
     * @param string $text
     * @param array $data
     * @return string
     */
    private function computeText($text, array $data)
    {
        $quote = $this->getQuote($data);

        $text = $this->computeTextForQuote($quote, $text);

        return $this->computeTextForUser($text, $data);
    }

    /**
     * @param array $data
     * @return Quote|null
     */
    private function getQuote(array $data)
    {
        if(isset($data['quote']) === false || ($data['quote'] instanceof Quote) === false) {
            return null;
        }

        return $data['quote'];
    }

    /**
     * @param array $data
     * @return User|null
     */
    private function getUser(array $data)
    {
        if(isset($data['user']) === false || ($data['user'] instanceof User) === false) {
            return $this->applicationContext->getCurrentUser();
        }

        return $data['user'];
    }

    /**
     * @param Quote $quote
     * @param string $text
     * @return string
     */
    private function computeTextForQuote(Quote $quote, $text)
    {
        $quoteFromRepository = $this->quoteRepository->getById($quote->id);
        $site = $this->siteRepository->getById($quote->siteId);
        $destination = $this->destinationRepository->getById($quote->destinationId);

        $countryName = $destination->countryName;

        $text = $this->replacesSummaryHtmlQuote($text, $quote);
        $text = $this->replacesSummaryQuote($text, $quote);
        $text = $this->replacesDestinationNameQuote($text, $countryName);

        return $this->replacesDestinationLinkQuote($text, $countryName, $site->url, $quoteFromRepository->id);
    }

    /**
     * @param string $text
     * @param string $countryName
     * @param string $url
     * @param string $id
     * @return string
     */
    private function replacesDestinationLinkQuote($text, $countryName, $url, $id)
    {
        if( strpos($text, '[quote:destination_link]') === false) {
            return str_replace('[quote:destination_link]', '', $text);
        }

        return str_replace('[quote:destination_link]', $url . '/' . $countryName . '/quote/' . $id, $text);
    }

    /**
     * @param string $text
     * @param string $countryName
     * @return string
     */
    private function replacesDestinationNameQuote($text, $countryName)
    {
        if( strpos($text, '[quote:destination_name]') === false) {
            return $text;
        }

        return str_replace('[quote:destination_name]', $countryName, $text);
    }

    /**
     * @param string $text
     * @param Quote $quote
     * @return string
     */
    private function replacesSummaryHtmlQuote($text, Quote $quote)
    {
        if( strpos($text, '[quote:summary_html]') === false) {
            return $text;
        }

        return str_replace('[quote:summary_html]', $this->renderer->renderHtml($quote->id), $text);
    }

    /**
     * @param string $text
     * @param Quote $quote
     * @return string
     */
    private function replacesSummaryQuote($text, Quote $quote)
    {
        if( strpos($text, '[quote:summary]') === false) {
            return $text;
        }

        return str_replace('[quote:summary]', $this->renderer->renderText($quote), $text);
    }

    /**
     * @param string $text
     * @param array $data
     * @return string
     */
    private function computeTextForUser($text, $data)
    {
        $user = $this->getUser($data);

        return $user ? str_replace('[user:first_name]', ucfirst(mb_strtolower($user->firstname)), $text) : $text;
    }
}
