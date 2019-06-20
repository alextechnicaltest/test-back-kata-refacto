<?php

use App\Context\ApplicationContext;
use App\DestinationComputeText;
use App\DestinationLinkComputeText;
use App\FirstNameComputeText;
use App\QuoteReplacer;
use App\Renderer;
use App\Repository\DestinationRepository;
use App\Repository\SiteRepository;
use App\SummaryComputeText;
use App\SummaryHtmlComputeText;

require_once __DIR__ . '/../vendor/autoload.php';

$faker = \Faker\Factory::create();

$template = new \App\Entity\Template(
    1,
    'Votre voyage avec une agence locale [quote:destination_name]',
    "
Bonjour [user:first_name],

Merci d'avoir contacté un agent local pour votre voyage [quote:destination_name].

Bien cordialement,

L'équipe Evaneos.com
www.evaneos.com
");

$quoteReplacer = new QuoteReplacer();
$renderer = new Renderer();

$subjectComputeText = new DestinationComputeText($quoteReplacer, DestinationRepository::getInstance());
$contentComputeText = new DestinationComputeText(
    $quoteReplacer,
    DestinationRepository::getInstance(),
    new FirstNameComputeText(
        $quoteReplacer,
        ApplicationContext::getInstance(),
        new SummaryHtmlComputeText(
            $quoteReplacer,
            $renderer,
            new SummaryComputeText(
                $quoteReplacer,
                $renderer,
                new DestinationLinkComputeText(
                    $quoteReplacer,
                    DestinationRepository::getInstance(),
                    SiteRepository::getInstance()
                )
            )
        )
    )
);

$templateManager = new \App\TemplateManager($subjectComputeText, $contentComputeText);

$message = $templateManager->getTemplateComputed(
    $template,
    [
        'quote' => new \App\Entity\Quote($faker->randomNumber(), $faker->randomNumber(), $faker->randomNumber(), $faker->date())
    ]
);

echo $message->subject . "\n" . $message->content;
