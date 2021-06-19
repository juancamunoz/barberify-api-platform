<?php


namespace App\Api\Listener\PreWrite;


use App\Entity\ScheduleDetail;
use App\Entity\User;
use App\Exception\ScheduleDetail\CannotCreateScheduleDetailForAnotherUserException;
use App\Exception\ScheduleDetail\NotAllowedDateTimeException;
use App\Repository\ScheduleDetailRepository;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class ScheduleDetailPreWriteListener implements PreWriteListener
{
    private const SCHEDULE_DETAIL_POST = 'api_schedule_details_post_collection';
    private const SCHEDULE_DETAIL_PUT = 'api_schedule_details_put_item';

    private const ALLOWED_DATE = '2020-01-01';

    private TokenStorageInterface $tokenStorage;
    private ScheduleDetailRepository $scheduleDetailRepository;

    public function __construct(TokenStorageInterface $tokenStorage, ScheduleDetailRepository $scheduleDetailRepository)
    {
        $this->tokenStorage = $tokenStorage;
        $this->scheduleDetailRepository = $scheduleDetailRepository;
    }

    public function onKernelView(ViewEvent $event): void
    {
        $request = $event->getRequest();

        if(in_array($request->get('_route'), [self::SCHEDULE_DETAIL_POST, self::SCHEDULE_DETAIL_PUT])){

            /** @var ScheduleDetail $scheduleDetail */
            $scheduleDetail = $event->getControllerResult();

            /** @var User $tokenUser */
            $tokenUser = $this->tokenStorage->getToken()
                ? $this->tokenStorage->getToken()->getUser()
                : null;

            $enterprise = $scheduleDetail->getSchedule()->getEnterprise();

            if(!$enterprise->isOwnedBy($tokenUser)){
                throw new CannotCreateScheduleDetailForAnotherUserException();
            }

            if(!$this->checkStartAndEndDates($scheduleDetail)){
                throw new NotAllowedDateTimeException();
            }

            $details = $this->scheduleDetailRepository->findAllByScheduleIdAndDay($scheduleDetail->getSchedule()->getId(), $scheduleDetail->getDay());
            foreach($details as $detail){
                if($detail->getId() === $scheduleDetail->getId()){
                    continue;
                }

                if($this->checkStartHourBetween($detail, $scheduleDetail) || $this->checkEndHourBetween($detail, $scheduleDetail)){
                    throw new BadRequestHttpException('Can not create that schedule hour');
                }
            }

        }
    }

    private function checkStartAndEndDates(ScheduleDetail $scheduleDetail): bool
    {
        return $scheduleDetail->getStartHour()->format('Y-m-d') === self::ALLOWED_DATE && $scheduleDetail->getEndHour()->format('Y-m-d') === self::ALLOWED_DATE;
    }

    private function checkStartHourBetween(ScheduleDetail $detail, ScheduleDetail $scheduleDetail): bool
    {
        return $detail->getStartHour() <= $scheduleDetail->getStartHour()
            && $scheduleDetail->getStartHour() < $detail->getEndHour();
    }

    private function checkEndHourBetween(ScheduleDetail $detail, ScheduleDetail $scheduleDetail): bool
    {
        return $detail->getStartHour() < $scheduleDetail->getEndHour()
            && $scheduleDetail->getEndHour() <= $detail->getEndHour();
    }
}