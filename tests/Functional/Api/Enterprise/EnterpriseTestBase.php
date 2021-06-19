<?php


namespace App\Tests\Functional\Api\Enterprise;


use App\Tests\Functional\TestBase;

class EnterpriseTestBase extends TestBase
{
    protected string $endpoint;

    public function setUp(): void
    {
        parent::setUp();
        $this->endpoint =  '/api/v1/enterprises';
    }
}