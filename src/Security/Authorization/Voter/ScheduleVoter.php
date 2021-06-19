<?php


namespace App\Security\Authorization\Voter;


use App\Entity\Schedule;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class ScheduleVoter extends Voter
{
    public const SCHEDULE_READ = 'SCHEDULE_READ';
    public const SCHEDULE_CREATE = 'SCHEDULE_CREATE';
    public const SCHEDULE_UPDATE = 'SCHEDULE_UPDATE';
    public const SCHEDULE_DELETE = 'SCHEDULE_DELETE';

    protected function supports(string $attribute, $subject): bool
    {
        return in_array($attribute, $this->getSupportedAttributes());
    }

    /**
     * @param Schedule $subject
     */
    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        if(self::SCHEDULE_CREATE === $attribute){
            return true;
        }

        if(in_array($attribute, [self::SCHEDULE_READ, self::SCHEDULE_UPDATE, self::SCHEDULE_DELETE])){
            /** @var User $tokenUser */
            $tokenUser = $token->getUser();

            return $subject->getEnterprise()->isOwnedBy($tokenUser);
        }

        return false;
    }

    private function getSupportedAttributes(): array
    {
        return [
            self::SCHEDULE_CREATE,
            self::SCHEDULE_READ,
            self::SCHEDULE_UPDATE,
            self::SCHEDULE_DELETE
        ];
    }
}