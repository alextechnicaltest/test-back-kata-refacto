<?php

namespace Tests\TextTransformer;

use App\Entity\Quote;
use App\TextTransformer\QuoteReplacer;
use App\TextTransformer\Renderer;
use App\TextTransformer\SummaryComputeText;
use PHPUnit_Framework_TestCase;

class SummaryComputeTextTest extends PHPUnit_Framework_TestCase
{
    public function test_it_replaces_the_placeholder()
    {
        $destinationComputeText = new SummaryComputeText(new QuoteReplacer(), new Renderer());

        $textWithPlaceholder = 'La summary est [quote:summary]';
        $data = ['quote' => new Quote(999, 1, 1, 1)];
        
        $this->assertSame('La summary est 999', $destinationComputeText->compute($textWithPlaceholder, $data));
    }
}