<?php


namespace App\Exception\Enterprise;


use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class CannotCreateEnterpriseForAnotherUserException extends AccessDeniedHttpException
{
    public function __construct()
    {
        parent::__construct('You can not create enterprises for another user');
    }
}