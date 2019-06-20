<?php


namespace Tests\TextTransformer;

use App\TextTransformer\Renderer;
use PHPUnit_Framework_TestCase;

class RendererTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Renderer
     */
    private $renderer;

    public function setUp()
    {
        parent::setUp();

        $this->renderer = new Renderer();
    }

    public function test_it_render_html()
    {
        $this->assertSame('<p>test</p>', $this->renderer->renderHtml('test'));
    }

    public function test_it_render_text()
    {
        $this->assertSame('99', $this->renderer->renderText(99));
    }
}