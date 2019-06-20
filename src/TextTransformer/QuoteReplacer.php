<?php

namespace App\TextTransformer;

class QuoteReplacer
{
    /**
     * @param string $quote
     * @param string $text
     * @param string $replace
     * @param string|null $default
     * @return string
     */
    public function replaceQuote($quote, $text, $replace, $default = null)
    {
        if( strpos($text, $quote) === false) {
            $replace = $default ? $default : $quote;
        }

        return str_replace($quote, $replace, $text);
    }
}