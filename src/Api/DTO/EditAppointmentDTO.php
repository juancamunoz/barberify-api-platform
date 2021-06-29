<?php


namespace App\Api\DTO;


use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;

class EditAppointmentDTO implements RequestDTO
{

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

        $this->date = $data['date'] ?? null;
        $this->duration = $data['duration'] ?? null;
    }


    public function getDate(): \DateTime
    {
        return new \DateTime($this->date);
    }

    public function getDuration(): int
    {
        return $this->duration;
    }

}