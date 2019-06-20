<?php


namespace App;


class QuoteReplacer
{
    public function replaceQuote($quote, $text, $replace, $default = null)
    {
        if( strpos($text, $quote) === false) {
            $replace = $default ? $default : $quote;
        }

        return str_replace($quote, $replace, $text);
    }
}