<?php


namespace App\Entity;


use Symfony\Component\Uid\Uuid;

class ScheduleDetail
{
    private string $id;
    private Schedule $schedule;
    private string $day;
    private \DateTime $startHour;
    private \DateTime $endHour;

    public function __construct(Schedule $schedule, string $day, \DateTime $startHour, \DateTime $endHour)
    {
        $this->id = Uuid::v4()->toRfc4122();
        $this->schedule = $schedule;
        $this->setDay($day);
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

    public function setSchedule(Schedule $schedule): void
    {
        $this->schedule = $schedule;
    }

    public function getDay(): string
    {
        return $this->day;
    }

    public function setDay(string $day): void
    {
        if(!in_array($day, ['monday', 'tuesday', 'wednesday', 'thursday', 'friday'])){
            throw new \DomainException(sprintf('Day %s is cannot be processable', $day));
        }

        $this->day = $day;
    }

    public function getStartHour(): \DateTime
    {
        return $this->startHour;
    }

    public function setStartHour(\DateTime $startHour): void
    {
        $this->startHour = $startHour;
    }

    public function getEndHour(): \DateTime
    {
        return $this->endHour;
    }

    public function setEndHour(\DateTime $endHour): void
    {
        $this->endHour = $endHour;
    }

}