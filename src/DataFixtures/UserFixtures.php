<?php
/**
 * User fixtures.
 */

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

/**
 * Class UserFixtures.
 */
class UserFixtures extends AbstractBaseFixtures implements DependentFixtureInterface
{
    /**
     * Password hasher.
     */
    private UserPasswordHasherInterface $passwordHasher;

    /**
     * Constructor.
     *
     * @param UserPasswordHasherInterface $passwordHasher Password hasher
     */
    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    /**
     * Load Data.
     */
    public function loadData(): void
    {
        $this->createMany(5, 'users', function ($i) {
            $user = new User();
            $user->setEmail(sprintf('user%d@example.com', $i));
            $user->setRoles([User::ROLE_USER]);
            $user->setPassword(
                $this->passwordHasher->hashPassword(
                    $user,
                    'user1234'
                )
            );
            $user->setUserData($this->getReference('usersData_'.$i));

            return $user;
        });

        $this->createMany(5, 'admins', function ($i) {
            $user = new User();
            $user->setEmail(sprintf('admin%d@example.com', $i));
            $user->setRoles([User::ROLE_ADMIN]);
            $user->setPassword(
                $this->passwordHasher->hashPassword(
                    $user,
                    'admin1234'
                )
            );
            $user->setUserData($this->getReference('usersDataAdmin_'.$i));

            return $user;
        });

        $this->manager->flush();
    }

    /**
     * Function getDependencies.
     *
     * @return string[] of dependencies
     */
    public function getDependencies(): array
    {
        return [UserDataFixtures::class];
    }
}
