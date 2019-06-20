<?php

namespace Tests\TextTransformer;

use App\Context\ApplicationContext;
use App\Entity\User;
use App\TextTransformer\FirstNameComputeText;
use App\TextTransformer\QuoteReplacer;
use PHPUnit_Framework_TestCase;
use Mockery;

class FirstNameComputeTextTest extends PHPUnit_Framework_TestCase
{
    public function test_it_replaces_the_placeholder()
    {
        $applicationContext = Mockery::mock(ApplicationContext::class);
        $applicationContext->shouldReceive('getCurrentUser')->andReturn(new User(9, 'John', 'Doe', 'john@doe.com' ));

        $destinationComputeText = new FirstNameComputeText(new QuoteReplacer(), $applicationContext);

        $textWithPlaceholder = 'Le prenom est [user:first_name]';
        $data = [];
        
        $this->assertSame('Le prenom est John', $destinationComputeText->compute($textWithPlaceholder, $data));
    }

    public function test_it_replaces_the_placeholder_when_user_is_set_in_data()
    {
        $applicationContext = Mockery::mock(ApplicationContext::class);

        $destinationComputeText = new FirstNameComputeText(new QuoteReplacer(), $applicationContext);

        $textWithPlaceholder = 'Le prenom est [user:first_name]';
        $data = ['user' => new User(88, 'John', 'Doe', 'jon@doe.com')];

        $this->assertSame('Le prenom est John', $destinationComputeText->compute($textWithPlaceholder, $data));
    }
}