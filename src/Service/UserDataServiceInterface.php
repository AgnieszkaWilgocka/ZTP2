<?php

namespace App\Service;

use App\Entity\UserData;

interface UserDataServiceInterface
{
    public function save(UserData $userData);
}