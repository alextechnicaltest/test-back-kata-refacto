<?php

namespace Tests;

use App\Entity\Quote;
use App\Entity\Template;
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
     * Init the mocks
     */
    public function setUp()
    {
        $this->templateManager = new TemplateManager();
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
        $expectedDestination = DestinationRepository::getInstance()->getById($destinationId = $this->generator->randomNumber());
        $expectedUser = ApplicationContext::getInstance()->getCurrentUser();

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
}
