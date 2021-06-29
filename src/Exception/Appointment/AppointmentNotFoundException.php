<?php


namespace App\Exception\Appointment;


use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class AppointmentNotFoundException extends NotFoundHttpException
{
    private const MESSAGE_NOT_FOUND_BY_ID = 'Appointment with id %s not found';

    public static function fromId(string $id): self
    {
        throw new self(sprintf(self::MESSAGE_NOT_FOUND_BY_ID, $id));
    }
}