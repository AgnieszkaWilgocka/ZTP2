<?php
/**
 * User fixtures
 */
namespace App\DataFixtures;

use App\DataFixtures\AbstractBaseFixtures;
use App\Entity\User;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

/**
 * Class UserFixtures
 */
class UserFixtures extends AbstractBaseFixtures implements DependentFixtureInterface
{
    /**
     * Password hasher
     *
     * @var UserPasswordHasherInterface
     */
    private UserPasswordHasherInterface $passwordHasher;

    /**
     * Constructor
     *
     * @param UserPasswordHasherInterface $passwordHasher
     */
    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    /**
     * Load Data
     *
     * @return void
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
     * Function getDependencies
     *
     * @return string[]
     *
     * @psalm-return array{0: UserDataFixtures::class}
     */
    public function getDependencies(): array
    {
        return [UserDataFixtures::class];
    }
}
