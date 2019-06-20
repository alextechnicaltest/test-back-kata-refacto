<?php

namespace App\TextTransformer;

interface ComputeText
{
    /**
     * @param string $initialText
     * @param array $data
     * @return string
     */
    public function compute($initialText, array $data);
}