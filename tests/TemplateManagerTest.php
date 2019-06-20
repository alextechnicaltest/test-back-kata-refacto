<?php

namespace Tests;

use App\Entity\Quote;
use App\Entity\Template;
use App\Repository\SiteRepository;
use App\TextTransformer\DestinationComputeText;
use App\TextTransformer\DestinationLinkComputeText;
use App\TextTransformer\FirstNameComputeText;
use App\TextTransformer\QuoteReplacer;
use App\TextTransformer\Renderer;
use App\TextTransformer\SummaryComputeText;
use App\TextTransformer\SummaryHtmlComputeText;
use Faker\Generator;
use Faker\Factory;
use App\TemplateManager;
use App\Repository\DestinationRepository;
use App\Context\ApplicationContext;
use PHPUnit_Framework_TestCase;

class TemplateManagerTest extends PHPUnit_Framework_TestCase
{
    /** @var TemplateManager */
    private $templateManager;

    /** @var Generator */
    private $generator;

    /**
     * @var DestinationRepository
     */
    private $destinationRepository;

    /**
     * @var SiteRepository
     */
    private $siteRepository;

    /**
     * @var ApplicationContext
     */
    private $applicationContext;

    /**
     * Init the mocks
     */
    public function setUp()
    {
        $quoteReplacer = new QuoteReplacer();
        $renderer = new Renderer();

        $this->applicationContext = new ApplicationContext();
        $this->destinationRepository = new DestinationRepository();
        $this->siteRepository = new SiteRepository();

        $subjectComputeText = new DestinationComputeText($quoteReplacer, $this->destinationRepository);
        $contentComputeText = new DestinationComputeText(
            $quoteReplacer,
            $this->destinationRepository,
            new FirstNameComputeText(
                $quoteReplacer,
                $this->applicationContext,
                new SummaryHtmlComputeText(
                    $quoteReplacer,
                    $renderer,
                    new SummaryComputeText(
                        $quoteReplacer,
                        $renderer,
                        new DestinationLinkComputeText(
                            $quoteReplacer,
                            $this->destinationRepository,
                            $this->siteRepository
                        )
                    )
                )
            )
        );

        $this->templateManager = new TemplateManager($subjectComputeText, $contentComputeText);

        $this->generator = Factory::create();
    }

    /**
     * Closes the mocks
     */
    public function tearDown()
    {
    }

    /**
     * @test
     */
    public function test()
    {
        $expectedDestination = $this->destinationRepository->getById($destinationId = $this->generator->randomNumber());
        $expectedUser = $this->applicationContext->getCurrentUser();

        $quote = new Quote($this->generator->randomNumber(), $this->generator->randomNumber(), $destinationId, $this->generator->date());

        $template = new Template(
            1,
            'Votre voyage avec une agence locale [quote:destination_name]',
            "
Bonjour [user:first_name],

Merci d'avoir contacté un agent local pour votre voyage [quote:destination_name].

Bien cordialement,

L'équipe Evaneos.com
www.evaneos.com
");

        $message = $this->templateManager->getTemplateComputed($template, ['quote' => $quote]);

        $this->assertEquals('Votre voyage avec une agence locale ' . $expectedDestination->countryName, $message->subject);
        $this->assertEquals("
Bonjour " . $expectedUser->firstname . ",

Merci d'avoir contacté un agent local pour votre voyage " . $expectedDestination->countryName . ".

Bien cordialement,

L'équipe Evaneos.com
www.evaneos.com
", $message->content);
    }

    public function test_it_changes_placeholders_for_destination_link_and_summary_and_summary_html()
    {
        $expectedSite = $this->siteRepository->getById($siteId = $this->generator->randomNumber());
        $expectedDestination = $this->destinationRepository->getById($destinationId = $this->generator->randomNumber());

        $quote = new Quote($this->generator->randomNumber(), $siteId, $destinationId, $this->generator->date());

        $template = new Template(
            2,
            '',
            "Pour retrouver votre destination: [quote:destination_link] / [quote:summary] / [quote:summary_html]");

        $message = $this->templateManager->getTemplateComputed($template, ['quote' => $quote]);

        $url = $expectedSite->url .'/' .$expectedDestination->countryName .'/quote/' .$quote->id;

        $expectedContent = "Pour retrouver votre destination: $url / {$quote->id} / <p>{$quote->id}</p>";

        $this->assertEquals($expectedContent, $message->content);
    }
}
