<?php

namespace App\Security\Voter;

use App\Entity\Quote;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Security;

class QuoteVoter extends Voter
{
    public const EDIT = 'QUOTE_EDIT';
    public const DELETE = 'QUOTE_DELETE';

    public function __construct(private Security $security)
    {
    }

    protected function supports(string $attribute, $subject): bool
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return in_array($attribute, [self::EDIT, self::DELETE])
            && $subject instanceof \App\Entity\Quote;
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        if (!$user instanceof User) {
            return false;
        }

        if ($this->security->isGranted('ROLE_ADMIN')) {
            return true;
        }

        switch ($attribute) {
            case self::EDIT:
                return $this->canEdit($subject, $user);
            case self::DELETE:
                return $this->canDelete($subject, $user);
                break;
        }

        return false;
    }

    public function canEdit(Quote $quote, User $user): bool
    {
        if ($user === $quote->getUser()) {
            return true;
        }

        return false;
    }

    public function canDelete(Quote $quote, User $user): bool
    {
        if ($user === $quote->getUser()) {
            return true;
        }

        return false;
    }
}
