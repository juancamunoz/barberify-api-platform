<?php


namespace App\Exception\Enterprise;


use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class CannotUpdateEnterpriseAvatarForAnotherUserException extends AccessDeniedHttpException
{
    public function __construct()
    {
        parent::__construct('You can not upload an avatar for this enterprise');
    }
}