<?php
/**
 * UserDataService interface.
 */

namespace App\Service;

use App\Entity\UserData;

/**
 * Class UserDataServiceInterface.
 */
interface UserDataServiceInterface
{
    /**
     * Action save.
     *
     * @param UserData $userData UserData
     *
     * @return mixed Mixed
     */
    public function save(UserData $userData);
}
