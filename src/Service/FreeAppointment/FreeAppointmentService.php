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

    /**
     * @return array|FreeAppointment[] $date
     */
    public function get(\DateTime $date, Schedule $schedule): array
    {
        $this->checkIfFreeAppointmentAndCreate($date, $schedule);

        return $this->freeAppointmentRepository->findAllByDateAndSchedule($date, $schedule->getId());
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

    private function createAllFreeAppointments(\DateTime $date, Schedule $schedule): void
    {
        $dateInterval = new \DateInterval('PT' . $schedule->getIntervalTime() . 'M');
        $dayName = strtolower($date->format('l'));
        $testing = 1;

        foreach($this->getDayScheduleDetails($schedule, $date, $dayName) as $scheduleDetail){
            for($i = $scheduleDetail->getStartHour(); $i<$scheduleDetail->getEndHour();){
                $endHour = (clone $i)->add($dateInterval);
                $freeAppointment = new FreeAppointment(
                    $schedule,
                    $date,
                    clone $i,
                    $endHour
                );
                $this->entityManager->persist($freeAppointment);
                $testing++;
                $i->add($dateInterval);
            }
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


    private function getDayScheduleDetails(Schedule $schedule, \DateTime $date, string $dayName): Collection
    {
        $this->entityManager->refresh($schedule);
        return $schedule->getDetails()->filter(function (ScheduleDetail $currentSchedule) use ($date, $dayName) {
            return $currentSchedule->getDay() === $dayName;
        });
    }
}