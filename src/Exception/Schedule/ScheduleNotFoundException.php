<?php


namespace App\Exception\Schedule;


use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ScheduleNotFoundException extends NotFoundHttpException
{
    public static function fromId(string $id): self
    {
        throw new self(sprintf('Schedule with id %s not found', $id));
    }

    public static function fromIdAndEnterpriseId(string $id, string $enterpriseId): self
    {
        throw new self(sprintf('Schedule with id %s and enterpriseId %s not found', $id, $enterpriseId));
    }
}