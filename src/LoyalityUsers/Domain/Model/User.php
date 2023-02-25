<?php

declare(strict_types=1);

namespace App\LoyalityUsers\Domain\Model;

use App\LoyalityUsers\Domain\Event\UserRegistered;
use App\LoyalityUsers\Domain\Exception\CannotRegisterUserException;
use App\LoyalityUsers\Domain\Service\NotifyNewUser;
use App\LoyalityUsers\Domain\ValueObject\Email;
use App\LoyalityUsers\Domain\ValueObject\PhoneNumber;
use App\Tool\DomainEvents\DomainEvents;
use App\Tool\DomainEvents\DomainEventsPublisher;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\UuidInterface;

#[ORM\Entity()]
#[ORM\Table(name: 'users')]
class User implements DomainEventsPublisher
{
    use DomainEvents;

    /** @psalm-immutable  */
    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    public UuidInterface $id;

    #[ORM\Column(length: 1024)]
    private string $fullName;

    #[ORM\Column(type: 'email_type', unique: true, nullable: true)]
    private ?Email $email;

    #[ORM\Column(type: 'phone_number_type', unique: true, nullable: true)]
    private ?PhoneNumber $phoneNumber;

    private function __construct(UuidInterface $id, string $fullName, ?Email $email, ?PhoneNumber $phoneNumber)
    {
        $this->id = $id;
        $this->fullName = $fullName;
        $this->email = $email;
        $this->phoneNumber = $phoneNumber;
    }

    /**
     * @throws CannotRegisterUserException
     */
    public static function registerNew(UuidInterface $id, string $fullName, ?Email $email, ?PhoneNumber $phoneNumber): self
    {
        if ($email === null || $phoneNumber === null) {
            throw new CannotRegisterUserException('Either email or phone number must be specified');
        }

        $user = new self($id, $fullName, $email, $phoneNumber);
        $user->rememberThat(new UserRegistered($id));

        return $user;
    }

    /**
     * @throws \Exception
     */
    public function sendRegistrationNotification(NotifyNewUser $notifyNewUser): void
    {
        $notifyNewUser($this->fullName, $this->email, $this->phoneNumber);
    }
}
