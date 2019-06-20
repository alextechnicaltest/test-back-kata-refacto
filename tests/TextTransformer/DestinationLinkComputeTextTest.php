<?php

namespace Tests\TextTransformer;

use App\Entity\Destination;
use App\Entity\Quote;
use App\Entity\Site;
use App\Repository\DestinationRepository;
use App\Repository\SiteRepository;
use App\TextTransformer\DestinationLinkComputeText;
use App\TextTransformer\QuoteReplacer;
use PHPUnit_Framework_TestCase;
use Mockery;

class DestinationLinkComputeTextTest extends PHPUnit_Framework_TestCase
{
    public function test_it_replaces_the_placeholder()
    {
        $destinationRepository = Mockery::mock(DestinationRepository::class);
        $destinationRepository->shouldReceive('getById')->with($destinationId = 5)->andReturn(new Destination($destinationId, 'Maroc', '', ''));

        $siteRepository = Mockery::mock(SiteRepository::class);
        $siteRepository->shouldReceive('getById')->with($siteId = 50)->andReturn(new Site($siteId, 'example.com'));

        $destinationComputeText = new DestinationLinkComputeText(new QuoteReplacer(), $destinationRepository, $siteRepository);

        $textWithPlaceholder = 'Url: [quote:destination_link]';
        $data = ['quote' => new Quote(4, $siteId, $destinationId, 1)];
        
        $this->assertSame("Url: example.com/Maroc/quote/4", $destinationComputeText->compute($textWithPlaceholder, $data));
    }
}