<?php

declare(strict_types=1);

namespace App\LoyalityUsers\Domain\Exception;

use App\Tool\ExceptionHandling\DomainException;

class CannotRegisterUserException extends DomainException
{
}
