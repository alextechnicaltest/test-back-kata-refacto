<?php

namespace Tests\TextTransformer;

use App\Entity\Quote;
use App\TextTransformer\QuoteReplacer;
use App\TextTransformer\Renderer;
use App\TextTransformer\SummaryHtmlComputeText;
use PHPUnit_Framework_TestCase;

class SummaryHtmlComputeTextTest extends PHPUnit_Framework_TestCase
{
    public function test_it_replaces_the_placeholder()
    {
        $destinationComputeText = new SummaryHtmlComputeText(new QuoteReplacer(), new Renderer());

        $textWithPlaceholder = 'La summary html est [quote:summary_html]';
        $data = ['quote' => new Quote(999, 1, 1, 1)];
        
        $this->assertSame('La summary html est <p>999</p>', $destinationComputeText->compute($textWithPlaceholder, $data));
    }
}