<?php

namespace App\TextTransformer;

class Renderer
{
    /**
     * @param string $id
     * @return string
     */
    public function renderHtml($id)
    {
        return '<p>' . $id . '</p>';
    }

    /**
     * @param mixed $id
     * @return string
     */
    public static function renderText($id)
    {
        return (string) $id;
    }
}