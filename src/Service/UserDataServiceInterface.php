<?php
/**
 * UserDataService interface
 */
namespace App\Service;

use App\Entity\UserData;

/**
 * Class UserDataServiceInterface
 */
interface UserDataServiceInterface
{
    /**
     * Action save
     *
     * @param UserData $userData
     *
     * @return mixed
     */
    public function save(UserData $userData);
}
