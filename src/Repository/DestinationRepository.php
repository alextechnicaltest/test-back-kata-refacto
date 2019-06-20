<?php

namespace App\Repository;

use App\Entity\Destination;
use Faker;

class DestinationRepository implements Repository
{
    /**
     * @param int $id
     *
     * @return Destination
     */
    public function getById($id)
    {
        // DO NOT MODIFY THIS METHOD

        $faker = Faker\Factory::create();
        $faker->seed($id);

        return new Destination(
            $id,
            $faker->country,
            'en',
            $faker->slug()
        );
    }
}
