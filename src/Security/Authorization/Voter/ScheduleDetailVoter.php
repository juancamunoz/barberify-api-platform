<?php


namespace App\Security\Authorization\Voter;


use App\Entity\Schedule;
use App\Entity\ScheduleDetail;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class ScheduleDetailVoter extends Voter
{
    public const SCHEDULE_DETAIL_CREATE = 'SCHEDULE_DETAIL_CREATE';
    public const SCHEDULE_DETAIL_READ = 'SCHEDULE_DETAIL_READ';
    public const SCHEDULE_DETAIL_UPDATE = 'SCHEDULE_DETAIL_UPDATE';
    public const SCHEDULE_DETAIL_DELETE = 'SCHEDULE_DETAIL_DELETE';

    protected function supports(string $attribute, $subject): bool
    {
        return in_array($attribute, $this->getSupportedAttributes());
    }

    /**
     * @param ScheduleDetail $subject
     */
    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        if(self::SCHEDULE_DETAIL_CREATE === $attribute){
            return true;
        }

        if(in_array($attribute, [self::SCHEDULE_DETAIL_READ, self::SCHEDULE_DETAIL_UPDATE, self::SCHEDULE_DETAIL_DELETE])){
            /** @var User $tokenUser */
            $tokenUser = $token->getUser();

            return $subject->getSchedule()->getEnterprise()->isOwnedBy($tokenUser);
        }

        return false;
    }

    private function getSupportedAttributes(): array
    {
        return [
            self::SCHEDULE_DETAIL_CREATE,
            self::SCHEDULE_DETAIL_READ,
            self::SCHEDULE_DETAIL_UPDATE,
            self::SCHEDULE_DETAIL_DELETE
        ];
    }
}