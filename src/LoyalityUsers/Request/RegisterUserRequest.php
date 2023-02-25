<?php

declare(strict_types=1);

namespace App\LoyalityUsers\Request;

use App\Tool\ArgumentResolving\RequestDTO;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @psalm-immutable
 */
class RegisterUserRequest implements RequestDTO
{
    public readonly string $fullName;

    #[Assert\Email]
    public readonly ?string $email;

    #[Assert\Regex("/^\+1[0-9]{10}$/")]
    public readonly ?string $phoneNumber;

    public function __construct(string $fullName, ?string $email, ?string $phoneNumber)
    {
        $this->fullName = $fullName;
        $this->email = $email;
        $this->phoneNumber = $phoneNumber;
    }
}
