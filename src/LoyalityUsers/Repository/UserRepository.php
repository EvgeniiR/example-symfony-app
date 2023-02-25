<?php

declare(strict_types=1);

namespace App\LoyalityUsers\Repository;

use App\LoyalityUsers\Domain\Model\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function add(User $user): void
    {
        $this->getEntityManager()->persist($user);
    }

    public function emailExist(string $email): bool
    {
        return $this->findOneBy(['email' => $email]) !== null;
    }

    public function phoneExist(string $phoneNumber): bool
    {
        return $this->findOneBy(['phoneNumber' => $phoneNumber]) !== null;
    }
}
