<?php

namespace App\Service;


use App\Entity\UserData;
use App\Repository\UserrDataRepository;

class UserDataService implements UserDataServiceInterface
{
    private UserrDataRepository $userDataRepository;

    public function __construct(UserrDataRepository $userDataRepository)
    {
        $this->userDataRepository = $userDataRepository;
    }

    public function save(UserData $userData)
    {
        $this->userDataRepository->save($userData);
    }
}