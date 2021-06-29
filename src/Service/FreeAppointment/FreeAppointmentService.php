<?php


namespace App\Service\FreeAppointment;


use App\Entity\FreeAppointment;
use App\Entity\Schedule;
use App\Entity\ScheduleDetail;
use App\Exception\FreeAppointment\CannotBookThisAppointmentException;
use App\Exception\FreeAppointment\FreeAppointmentNotFound;
use App\Repository\FreeAppointmentRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManagerInterface;

class FreeAppointmentService
{
    private FreeAppointmentRepository $freeAppointmentRepository;
    private EntityManagerInterface $entityManager;

    public function __construct(FreeAppointmentRepository $freeAppointmentRepository, EntityManagerInterface $entityManager)
    {
        $this->freeAppointmentRepository = $freeAppointmentRepository;
        $this->entityManager = $entityManager;
    }

    public function getFirstAvailableAppointment(\DateTime $date, Schedule $schedule): FreeAppointment
    {
        $this->checkIfFreeAppointmentAndCreate($date, $schedule);

        return $this->freeAppointmentRepository->findOneByDateAndStartHourAndScheduleOrFail($date, $schedule->getId());
    }

    public function isAvailableAppointmentOrFail(\DateTime $date, Schedule $schedule): FreeAppointment
    {
        try{
            return $this->freeAppointmentRepository->findOneByDateAndStartHourAndScheduleOrFail($date, $schedule->getId());
        }catch (FreeAppointmentNotFound $exception){
            throw new CannotBookThisAppointmentException();
        }
    }

    /** @var array|FreeAppointment[] $freeAppointments */
    public function delete(array $freeAppointments): void
    {
        foreach($freeAppointments as $appointment){
            $this->entityManager->remove($appointment);
        }
        $this->entityManager->flush();
    }


    private function checkIfFreeAppointmentAndCreate(\DateTime $date, Schedule $schedule): void
    {
        $freeAppointments = $this->freeAppointmentRepository->findAllByDateAndSchedule($date, $schedule->getId());

        if(count($freeAppointments) == 0){
            $this->createAllFreeAppointments($date, $schedule);
        }
    }

    private function createAllFreeAppointments(\DateTime $date, Schedule $schedule): void
    {
        foreach($this->getScheduleDetailsByDayName($schedule, $date) as $scheduleDetail){
            $this->iterateAndAddFreeAppointment($scheduleDetail, $schedule, $date);
        }
        $this->entityManager->flush();
    }

    public function createFreeAppointmentsFromRange(\DateTime $date, Schedule $schedule, \DateTime $from, \DateTime $to): void
    {
        $this->iterateAndAddFreeAppointmentFromRange($schedule, $date, $from, $to);
        $this->entityManager->flush();
    }


    private function getScheduleDetailsByDayName(Schedule $schedule, \DateTime $date): Collection
    {
        $this->entityManager->refresh($schedule);
        $dayName = strtolower($date->format('l'));

        return $schedule->getDetails()->filter(function (ScheduleDetail $currentSchedule) use ($date, $dayName) {
            return $currentSchedule->getDay() === $dayName;
        });
    }

    private function iterateAndAddFreeAppointment($scheduleDetail, Schedule $schedule, \DateTime $date): void
    {
        $minutesToAdd = new \DateInterval('PT' . $schedule->getIntervalTime() . 'M');

        for ($startHour = $scheduleDetail->getStartHour(); $startHour < $scheduleDetail->getEndHour();) {
            $endHour = (clone $startHour)->add($minutesToAdd);
            $freeAppointment = new FreeAppointment(
                $schedule,
                $date,
                clone $startHour,
                $endHour
            );
            $this->entityManager->persist($freeAppointment);
            $startHour->add($minutesToAdd);
        }
    }

    private function iterateAndAddFreeAppointmentFromRange(Schedule $schedule, \DateTime $date, \DateTime $from, \DateTime $to): void
    {
        $minutesToAdd = new \DateInterval('PT' . $schedule->getIntervalTime() . 'M');

        for ($startHour = $from; $startHour < $to;) {
            $endHour = (clone $startHour)->add($minutesToAdd);
            $freeAppointment = new FreeAppointment(
                $schedule,
                $date,
                clone $startHour,
                $endHour
            );
            $this->entityManager->persist($freeAppointment);
            $startHour->add($minutesToAdd);
        }
    }
}