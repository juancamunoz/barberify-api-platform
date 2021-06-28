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
    private ?DateTime $date;

    /**
     * @Assert\NotBlank()
     * @Assert\GreaterThan(1)
     * @Assert\LessThan(300)
     */
    private ?int $duration;

    public function __construct(Request $request)
    {
        $data = json_decode($request, true);

        $this->ownerId = $data['owner'];
        $this->enterpriseId = $data['enterprise'];
        $this->scheduleId = $data['schedule'];
        $this->date = $data['date'];
        $this->duration = $data['duration'];
    }

    public function getOwnerId()
    {
        return $this->ownerId;
    }

    public function getEnterpriseId()
    {
        return $this->enterpriseId;
    }

    public function getScheduleId()
    {
        return $this->scheduleId;
    }

    public function getDate()
    {
        return $this->date;
    }

    public function getDuration()
    {
        return $this->duration;
    }

}