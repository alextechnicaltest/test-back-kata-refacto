<?php

namespace Tests\TextTransformer;

use App\TextTransformer\QuoteReplacer;

class QuoteReplacerTest extends \PHPUnit_Framework_TestCase
{
    public function test_it_replace_the_quote()
    {
        $quoteReplacer = new QuoteReplacer();

        $this->assertSame('Test Test', $quoteReplacer->replaceQuote('[test]', 'Test [test]', 'Test'));
    }
}