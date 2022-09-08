<?php
/**
 * UserData service
 */
namespace App\Service;

use App\Entity\UserData;
use App\Repository\UserrDataRepository;

/**
 * Class UserDataService
 *
 */
class UserDataService implements UserDataServiceInterface
{
    private UserrDataRepository $userDataRepository;

    /**
     * Constructor
     *
     * @param UserrDataRepository $userDataRepository
     */
    public function __construct(UserrDataRepository $userDataRepository)
    {
        $this->userDataRepository = $userDataRepository;
    }

    /**
     * Action save
     *
     * @param UserData $userData
     *
     * @return void
     */
    public function save(UserData $userData)
    {
        $this->userDataRepository->save($userData);
    }
}
