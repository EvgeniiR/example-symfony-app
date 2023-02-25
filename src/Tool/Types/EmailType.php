<?php

declare(strict_types=1);

namespace App\Tool\Types;

use App\LoyalityUsers\Domain\ValueObject\Email;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\Type;

class EmailType extends Type
{
    public const MYTYPE = 'email_type';

    public function getSQLDeclaration(array $column, AbstractPlatform $platform): mixed
    {
        return $platform->getStringTypeDeclarationSQL($column);
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): ?Email
    {
        /** @var ?string $value */
        if ($value === null) {
            return $value;
        }

        return new Email($value);
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform): ?string
    {
        if ($value === null) {
            return $value;
        }

        if (is_string($value)) {
            return $value;
        }

        if ($value instanceof Email) {
            return $value->email;
        }

        throw ConversionException::conversionFailedInvalidType($value, $this->getName(), ['null', 'string', 'Email']);
    }

    public function getName(): string
    {
        return self::MYTYPE;
    }
}
