<?php


namespace App\Entity;


use Symfony\Component\Uid\Uuid;

class Appointment
{

    private string $id;
    private User $owner;
    private Enterprise $enterprise;
    private Schedule $schedule;
    private \DateTime $date;
    private int $duration;
    private \DateTime $createdAt;
    private \DateTime $updatedAt;

    public function __construct(User $owner, Enterprise $enterprise, Schedule $schedule, \DateTime $date, int $duration)
    {
        $this->id = Uuid::v4()->toRfc4122();
        $this->owner = $owner;
        $this->enterprise = $enterprise;
        $this->schedule = $schedule;
        $this->date = $date;
        $this->duration = $duration;
        $this->createdAt = new \DateTime();
        $this->markAsUpdated();
    }

    public function getId(): string
    {
        return $this->id;
    }


    public function getOwner(): User
    {
        return $this->owner;
    }

    public function getEnterprise(): Enterprise
    {
        return $this->enterprise;
    }

    public function getSchedule(): Schedule
    {
        return $this->schedule;
    }

    public function getDate(): \DateTime
    {
        return $this->date;
    }

    public function setDate(\DateTime $date): void
    {
        $this->date = $date;
    }

    public function getDuration(): int
    {
        return $this->duration;
    }

    public function setDuration(int $duration): void
    {
        $this->duration = $duration;
    }

    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): \DateTime
    {
        return $this->updatedAt;
    }

    public function markAsUpdated(): void
    {
        $this->updatedAt = new \DateTime();
    }


}