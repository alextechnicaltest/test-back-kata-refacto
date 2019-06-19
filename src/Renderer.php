<?php


namespace App;

class Renderer
{
    public function renderHtml($id)
    {
        return '<p>' . $id . '</p>';
    }

    public static function renderText($id)
    {
        return (string) $id;
    }
}