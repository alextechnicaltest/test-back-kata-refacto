<?php

use App\Context\ApplicationContext;
use App\Repository\DestinationRepository;
use App\Repository\SiteRepository;
use App\TextTransformer\QuoteReplacer;
use App\TextTransformer\Renderer;
use App\TextTransformer\DestinationComputeText;
use App\TextTransformer\FirstNameComputeText;
use App\TextTransformer\SummaryHtmlComputeText;
use App\TextTransformer\SummaryComputeText;
use App\TextTransformer\DestinationLinkComputeText;

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
$destinationRepository = new DestinationRepository();
$applicationContext = new ApplicationContext();
$siteRepository = new SiteRepository();

$subjectComputeText = new DestinationComputeText($quoteReplacer, $destinationRepository);
$contentComputeText = new DestinationComputeText(
    $quoteReplacer,
    $destinationRepository,
    new FirstNameComputeText(
        $quoteReplacer,
        $applicationContext,
        new SummaryHtmlComputeText(
            $quoteReplacer,
            $renderer,
            new SummaryComputeText(
                $quoteReplacer,
                $renderer,
                new DestinationLinkComputeText(
                    $quoteReplacer,
                    $destinationRepository,
                    $siteRepository
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
