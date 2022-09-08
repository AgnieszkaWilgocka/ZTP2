<?php
/**
 * Comment voter
 */
namespace App\Security\Voter;

use App\Entity\Comment;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class CommentVoter
 *
 */
class CommentVoter extends Voter
{
    public const DELETE = 'DELETE';

    private Security $security;

    /**
     * Constructor
     *
     * @param Security $security
     */
    public function __construct(Security $security)
    {
        $this->security = $security;
    }

//    public const VIEW = 'POST_VIEW';

    /**
     * Determines if the attribute and subject are supported by this voter
     *
     * @param string $attribute
     * @param $subject
     *
     * @return bool
     */
    protected function supports(string $attribute, $subject): bool
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return in_array($attribute, [self::DELETE])
            && $subject instanceof Comment;
    }

    /**
     * Perform a single access check operation on a given attribute, subject and token.
     * It is safe to assume that $attribute and $subject already passed the "supports()" method check
     *
     * @param string         $attribute
     * @param $subject
     * @param TokenInterface $token
     *
     * @return bool
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
            case self::DELETE:
                return $this->canDelete($subject, $user);
                // logic to determine if the user can EDIT
                // return true or false
//                break;
        }

        return false;
    }

    /**
     * @param Comment $comment
     * @param User    $user
     *
     * @return bool
     */
    private function canDelete(Comment $comment, User $user):bool
    {
        return $comment->getAuthor() === $user || $this->security->isGranted("ROLE_ADMIN", $user);
    }
}
