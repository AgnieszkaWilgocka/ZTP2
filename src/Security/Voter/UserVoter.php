<?php
/**
 * User voter.
 */

namespace App\Security\Voter;

use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class UserVoter.
 */
class UserVoter extends Voter
{
//    /**
//     * Edit permission
//     *
//     * @const string
//     */
//    public const EDIT = 'POST_EDIT';
//
//    /**
//     * View permission
//     *
//     * @const string
//     */
//    public const VIEW = 'POST_VIEW';
//
//    /**
//     * Delete permission
//     *
//     * @const string
//     */
//    public const DELETE = 'POST_DELETE';

    /**
     * Security helper.
     */
    private Security $security;

    /**
     * Constructor.
     *
     * @param Security $security Security
     */
    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    /**
     * Determines if the attribute and subject are supported by this voter.
     *
     * @param string $attribute Attribute
     * @param        $subject
     *
     * @return bool Bool
     */
    protected function supports(string $attribute, $subject): bool
    {
        return in_array($attribute, ['USER_VIEW', 'USER_EDIT', 'USER_DELETE'])
            && $subject instanceof User;
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
//        return in_array($attribute, [self::EDIT, self::VIEW])
//            && $subject instanceof \App\Entity\User;
    }

    /**
     * Perform a single access check operation on a given attribute, subject and token.
     * It is safe to assume that $attribute and $subject already passed the "supports()" method check.
     *
     * @param string         $attribute Attribute
     * @param                $subject
     * @param TokenInterface $token     Token
     *
     * @return bool Bool
     */
    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        // ... (check conditions and return true to grant permission) ...
        switch ($attribute) {
            case 'USER_VIEW':
//                return $this->canEdit($subject, $user);
                // logic to determine if the user can EDIT
                // return true or false
//                break;
            case 'USER_EDIT':
//                return $this->canView($subject, $user);
                // logic to determine if the user can VIEW
                // return true or false
//                break;
            case 'USER_DELETE':
                if ($subject === $user || $this->security->isGranted('ROLE_ADMIN', $user)) {
                    return true;
                }
                break;
            default:
                return false;
                break;

//                return $this->canDelete($subject, $user);
        }

        return false;
    }

//    private function canEdit(User $user): bool
//    {
//        return $user->get
//    }
}
