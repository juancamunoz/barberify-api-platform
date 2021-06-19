<?php


namespace App\Exception\FreeAppointment;


use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class FreeAppointmentNotFound extends NotFoundHttpException
{
    public static function fromScheduleIdAndDate(string $scheduleId, string $date): self
    {
        throw new self(sprintf('Free Appointment with schedule id %s and date %s not found', $scheduleId, $date));
    }
}