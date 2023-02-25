<?php

declare(strict_types=1);

namespace App\LoyalityUsers\Domain\Service;

use App\LoyalityUsers\Domain\Exception\CannotRegisterUserException;
use App\LoyalityUsers\Domain\Model\User;
use App\LoyalityUsers\Domain\ValueObject\Email;
use App\LoyalityUsers\Domain\ValueObject\PhoneNumber;
use App\LoyalityUsers\Repository\UserRepository;
use Ramsey\Uuid\UuidInterface;

class RegisterUser
{
    private readonly UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @throws CannotRegisterUserException
     */
    public function __invoke(UuidInterface $id, string $fullName, ?Email $email, ?PhoneNumber $phoneNumber): void
    {
        if ($email !== null && $this->userRepository->emailExist($email->email)) {
            throw new CannotRegisterUserException('Email not unique');
        }

        if ($phoneNumber !== null && $this->userRepository->phoneExist($phoneNumber->phoneNumber)) {
            throw new CannotRegisterUserException('Phone not unique');
        }

        $user = User::registerNew($id, $fullName, $email, $phoneNumber);
        $this->userRepository->add($user);
    }
}
