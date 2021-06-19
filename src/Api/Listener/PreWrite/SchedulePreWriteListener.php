<?php


namespace App\Api\Listener\PreWrite;


use App\Entity\Schedule;
use App\Entity\User;
use App\Exception\Schedule\CannotCreateScheduleException;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class SchedulePreWriteListener implements PreWriteListener
{
    private const SCHEDULE_POST = 'api_schedules_post_collection';
    private const SCHEDULE_PUT = 'api_schedules_put_item';

    private TokenStorageInterface $tokenStorage;

    public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }

    public function onKernelView(ViewEvent $event): void
    {
        $request = $event->getRequest();

        if(in_array($request->get('_route'), [self::SCHEDULE_POST, self::SCHEDULE_PUT])){
            /** @var Schedule $schedule */
            $schedule = $event->getControllerResult();

            /** @var User $tokenUser */
            $tokenUser = $this->tokenStorage->getToken()
                ? $this->tokenStorage->getToken()->getUser()
                : null;

            if(!$schedule->getEnterprise()->isOwnedBy($tokenUser)){
                throw CannotCreateScheduleException::fromEnterpriseIdAndUserId(
                    $schedule->getEnterprise()->getId(),
                    $tokenUser->getId()
                );
            }

            if($schedule->getDateFrom() >= $schedule->getDateTo()){
                throw new BadRequestHttpException('Can not create schedule with invalid dates');
            }
        }

    }
}