<?php


namespace App\Security\Authorization\Voter;


use App\Entity\Enterprise;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class EnterpriseVoter extends Voter
{

    public const ENTERPRISE_CREATE = 'ENTERPRISE_CREATE';
    public const ENTERPRISE_READ = 'ENTERPRISE_READ';
    public const ENTERPRISE_UPDATE = 'ENTERPRISE_UPDATE';
    public const ENTERPRISE_DELETE = 'ENTERPRISE_DELETE';

    protected function supports(string $attribute, $subject): bool
    {
        return \in_array($attribute, $this->getSupportedAttributes(), true);
    }

    /**
     * @param Enterprise|null $subject
     */
    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        /** @var User $tokenUser */
        $tokenUser = $token->getUser();

        if (self::ENTERPRISE_CREATE === $attribute) {
            return true;
        }

        if(in_array($attribute, $this->getSupportedAttributes())){
            return $tokenUser->equals($subject->getOwner());
        }

        return false;
    }

    private function getSupportedAttributes(): array
    {
        return [
            self::ENTERPRISE_CREATE,
            self::ENTERPRISE_READ,
            self::ENTERPRISE_UPDATE,
            self::ENTERPRISE_DELETE
        ];
    }
}