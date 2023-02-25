<?php

declare(strict_types=1);

namespace App\Tests\api;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class LoyalityApiRegistrationTest extends WebTestCase
{
    public function testNewUserRegistrationSuccess(): void
    {
        static::createClient()->jsonRequest('POST', '/user/register', [
            'fullName' => 'Andrew Andrew',
            'email' => 'user123@test.com',
            'phoneNumber' => '+14966851891',
        ]);

        $this->assertResponseIsSuccessful();
    }

    public function testNewUserRegistrationInvalidEmail(): void
    {
        static::createClient()->jsonRequest('POST', '/user/register', [
            'fullName' => 'Evgenii R',
            'email' => 'some-invalid-email',
            'phoneNumber' => '+14966851891',
        ]);

        $this->assertResponseStatusCodeSame(400);
    }

    public function testNewUserRegistrationEmailAlreadyExist(): void
    {
        $client = static::createClient();
        $client->jsonRequest('POST', '/user/register', [
            'fullName' => 'Evgenii R',
            'email' => 'user123@test.com',
            'phoneNumber' => '+14956851891',
        ]);

        $this->assertResponseIsSuccessful();

        $client->jsonRequest('POST', '/user/register', [
            'fullName' => 'Evgenii R',
            'email' => 'user123@test.com',
            'phoneNumber' => '+14956851891',
        ]);

        $this->assertResponseStatusCodeSame(400);
    }
}
