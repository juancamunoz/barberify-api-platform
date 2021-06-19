<?php


namespace App\Entity;


use Symfony\Component\Uid\Uuid;

class FreeAppointment
{
    private string $id;
    private Schedule $schedule;
    private \DateTime $date;
    private \DateTime $startHour;
    private \DateTime $endHour;

    public function __construct(Schedule $schedule, \DateTime $date, \DateTime $startHour, \DateTime $endHour)
    {
        $this->id = Uuid::v4()->toRfc4122();
        $this->schedule = $schedule;
        $this->date = $date;
        $this->startHour = $startHour;
        $this->endHour = $endHour;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getSchedule(): Schedule
    {
        return $this->schedule;
    }

    public function getDate(): \DateTime
    {
        return $this->date;
    }

    public function getStartHour(): \DateTime
    {
        return $this->startHour;
    }

    public function getEndHour(): \DateTime
    {
        return $this->endHour;
    }

}