<?php


namespace App\Exception\Schedule;


use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class CannotCreateScheduleException extends AccessDeniedHttpException
{
    private const MESSAGE_FOR_ENTERPRISE_OF_ANOTHER_USER = 'This enterprise %s does not belongs to this user id %s';

    public static function fromEnterpriseIdAndUserId($enterpriseId, $userId): self
    {
        throw new self(sprintf(self::MESSAGE_FOR_ENTERPRISE_OF_ANOTHER_USER, $enterpriseId, $userId));
    }
}