<?php

namespace Tests\TextTransformer;

use App\Entity\Destination;
use App\Entity\Quote;
use App\Repository\DestinationRepository;
use App\TextTransformer\DestinationComputeText;
use App\TextTransformer\QuoteReplacer;
use PHPUnit_Framework_TestCase;
use Mockery;

class DestinationComputeTextTest extends PHPUnit_Framework_TestCase
{
    public function test_it_replaces_the_placeholder()
    {
        $destinationRepository = Mockery::mock(DestinationRepository::class);
        $destinationRepository->shouldReceive('getById')->with($destinationId = 5)->andReturn(new Destination($destinationId, 'Maroc', '', ''));

        $destinationComputeText = new DestinationComputeText(new QuoteReplacer(), $destinationRepository);

        $textWithPlaceholder = 'La destination est [quote:destination_name]';
        $data = ['quote' => new Quote(1, 1, $destinationId, 1)];
        
        $this->assertSame('La destination est Maroc', $destinationComputeText->compute($textWithPlaceholder, $data));
    }
}