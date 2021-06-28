<?php

namespace App\Api\DTO;

use DateTime;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;

class CreateAppointmentDTO implements RequestDTO
{
    /**
     * @Assert\NotBlank()
     * @Assert\Uuid()
     */
    private ?string $ownerId;

    /**
     * @Assert\NotBlank()
     * @Assert\Uuid()
     */
    private ?string $enterpriseId;

    /**
     * @Assert\NotBlank()
     * @Assert\Uuid()
     */
    private ?string $scheduleId;

    /**
     * @Assert\NotBlank()
     * @Assert\DateTime()
     */
    private ?string $date;

    /**
     * @Assert\NotBlank()
     * @Assert\GreaterThan(1)
     * @Assert\LessThan(300)
     */
    private ?int $duration;

    public function __construct(Request $request)
    {
        $data = json_decode($request->getContent(), true);

        $this->ownerId = $data['owner'];
        $this->enterpriseId = $data['enterprise'];
        $this->scheduleId = $data['schedule'];
        $this->date = $data['date'];
        $this->duration = (int) $data['duration'];
    }

    public function getOwnerId(): string
    {
        return $this->ownerId;
    }

    public function getEnterpriseId(): string
    {
        return $this->enterpriseId;
    }

    public function getScheduleId(): string
    {
        return $this->scheduleId;
    }

    public function getDate(): DateTime
    {
        return new DateTime($this->date);
    }

    public function getDuration(): int
    {
        return $this->duration;
    }

}