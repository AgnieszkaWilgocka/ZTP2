<?php
/**
 * UserData fixtures.
 */

namespace App\DataFixtures;

use App\Entity\UserData;

/**
 * Class UserDataFixtures.
 */
class UserDataFixtures extends AbstractBaseFixtures
{
    /**
     * Load data.
     */
    public function loadData(): void
    {
        $this->createMany(5, 'usersData', function ($i) {
            $userData = new UserData();
            $userData->setNick($this->faker->name);

            return $userData;
        });

        $this->createMany(5, 'usersDataAdmin', function ($i) {
            $userData = new UserData();
            $userData->setNick($this->faker->name);

            return $userData;
        });

        $this->manager->flush();
    }
}
