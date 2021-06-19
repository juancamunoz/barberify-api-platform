<?php


namespace App\Entity;


use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Uid\Uuid;

class Schedule
{
    private string $id;
    private Enterprise $enterprise;
    private \DateTime $dateFrom;
    private \DateTime $dateTo;
    private int $intervalTime;
    private Collection $details;
    private Collection $freeAppointments;

    public function __construct(Enterprise $enterprise, \DateTime $dateFrom, \DateTime $dateTo, int $intervalTime)
    {
        $this->id = Uuid::v4()->toRfc4122();
        $this->enterprise = $enterprise;
        $this->dateFrom = $dateFrom;
        $this->dateTo = $dateTo;
        $this->intervalTime = $intervalTime;
        $this->details = new ArrayCollection();
        $this->freeAppointments = new ArrayCollection();
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getEnterprise(): Enterprise
    {
        return $this->enterprise;
    }


    public function getDateFrom(): \DateTime
    {
        return $this->dateFrom;
    }

    public function setDateFrom(\DateTime $dateFrom): void
    {
        $this->dateFrom = $dateFrom;
    }

    public function getDateTo(): \DateTime
    {
        return $this->dateTo;
    }

    public function setDateTo(\DateTime $dateTo): void
    {
        $this->dateTo = $dateTo;
    }

    public function getIntervalTime(): int
    {
        return $this->intervalTime;
    }

    public function setIntervalTime(int $intervalTime): void
    {
        $this->intervalTime = $intervalTime;
    }

    /**
     * @return Collection|ScheduleDetail[]
     */
    public function getDetails(): Collection
    {
        return $this->details;
    }

    /**
     * @return Collection|FreeAppointment[]
     */
    public function getFreeAppointments(): Collection
    {
        return $this->freeAppointments;
    }

}