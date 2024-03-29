<?php

namespace App\TextTransformer;

use App\Context\ApplicationContext;
use App\Entity\User;

class FirstNameComputeText implements ComputeText
{
    private $quoteReplacer;
    private $computeText;
    private $applicationContext;

    /**
     * @param QuoteReplacer $quoteReplacer
     * @param ApplicationContext $applicationContext
     * @param ComputeText|null $computeText
     */
    public function __construct(
        QuoteReplacer $quoteReplacer,
        ApplicationContext $applicationContext,
        ComputeText $computeText = null
    ) {
        $this->quoteReplacer = $quoteReplacer;
        $this->computeText = $computeText;
        $this->applicationContext = $applicationContext;
    }

    /**
     * @param string $initialText
     * @param array $data
     * @return string
     */
    public function compute($initialText, array $data)
    {
        $user = $this->getUser($data);

        $text = $this->quoteReplacer->replaceQuote(
            '[user:first_name]',
            $initialText,
            ucfirst(mb_strtolower($user->firstname))
        );

        return $this->computeText ? $this->computeText->compute($text, $data) : $text;
    }

    /**
     * @param array $data
     * @return User|null
     */
    private function getUser(array $data)
    {
        if(isset($data['user']) === false || ($data['user'] instanceof User) === false) {
            return $this->applicationContext->getCurrentUser();
        }

        return $data['user'];
    }
}