<?php

namespace App\Repository;

use App\Entity\Quote;
use Faker\Factory;

class QuoteRepository implements Repository
{
    /**
     * @param int $id
     *
     * @return Quote
     */
    public function getById($id)
    {
        $generator = Factory::create();
        $generator->seed($id);
        return new Quote(
            $id,
            $generator->numberBetween(1, 10),
            $generator->numberBetween(1, 200),
            $generator->dateTime()
        );
    }
}
