<?php

declare(strict_types=1);

namespace App\LoyalityUsers;

use App\LoyalityUsers\Domain\Service\RegisterUser;
use App\LoyalityUsers\Domain\ValueObject\Email;
use App\LoyalityUsers\Domain\ValueObject\PhoneNumber;
use App\LoyalityUsers\Request\RegisterUserRequest;
use Doctrine\Persistence\ManagerRegistry;
use Ramsey\Uuid\Uuid;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class RegistrationController extends AbstractController
{
    private readonly ManagerRegistry $doctrine;

    public function __construct(ManagerRegistry $doctrine)
    {
        $this->doctrine = $doctrine;
    }

    /**
     * @throws \Exception
     */
    #[Route('/user/register', name: 'user_register', methods: ['POST'])]
    public function registerUser(
        RegisterUserRequest $request,
        RegisterUser $registerUser
    ): JsonResponse {
        $registerUser(
            Uuid::uuid4(),
            $request->fullName,
            $request->email !== null ? new Email($request->email) : null,
            $request->phoneNumber !== null ? new PhoneNumber($request->phoneNumber) : null
        );

        $this->doctrine->getManager()->flush();

        return new JsonResponse([
            'status' => 'success',
            'message' => 'User registered',
        ]);
    }
}
