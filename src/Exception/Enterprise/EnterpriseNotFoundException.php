<?php


namespace App\Exception\Enterprise;


use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class EnterpriseNotFoundException extends NotFoundHttpException
{
    private const MESSAGE_FROM_ID = 'Enterprise with id %s not found';

    public static function fromId(string $id): self
    {
        throw new self(sprintf(self::MESSAGE_FROM_ID, $id));
    }
}