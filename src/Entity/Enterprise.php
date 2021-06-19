<?php


namespace App\Entity;


use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Uid\Uuid;

class Enterprise
{
    private string $id;
    private User $owner;
    private string $name;
    private string $location;
    private ?string $avatar;
    private Collection $schedules;
    private Collection $appointments;

    public function __construct(string $name, string $location, User $owner)
    {
        $this->id = Uuid::v4()->toRfc4122();
        $this->name = $name;
        $this->location = $location;
        $this->owner = $owner;
        $this->avatar = null;
        $this->schedules = new ArrayCollection();
        $this->appointments = new ArrayCollection();
    }

    public function getId(): string
    {
        return $this->id;
    }


    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getLocation(): string
    {
        return $this->location;
    }

    public function setLocation(string $location): void
    {
        $this->location = $location;
    }

    public function getOwner(): User
    {
        return $this->owner;
    }

    public function getAvatar(): ?string
    {
        return $this->avatar;
    }

    public function setAvatar(?string $avatar): void
    {
        $this->avatar = $avatar;
    }

    public function isOwnedBy(User $user): bool
    {
        return $this->owner->equals($user);
    }

    /**
     * @return Collection|Schedule[]
     */
    public function getSchedules(): Collection
    {
        return $this->schedules;
    }

    /**
     * @return Collection|Appointment[]
     */
    public function getAppointments(): Collection
    {
        return $this->appointments;
    }

}