<?php

declare(strict_types=1);

namespace App\Tool\Types;

use App\LoyalityUsers\Domain\ValueObject\PhoneNumber;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\Type;

class PhoneNumberType extends Type
{
    public const MYTYPE = 'phone_number_type';

    public function getSQLDeclaration(array $column, AbstractPlatform $platform): mixed
    {
        return $platform->getStringTypeDeclarationSQL($column);
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): ?PhoneNumber
    {
        /** @var ?string $value */
        if ($value === null) {
            return $value;
        }

        return new PhoneNumber($value);
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform): ?string
    {
        if ($value === null) {
            return $value;
        }

        if (is_string($value)) {
            return $value;
        }

        if ($value instanceof PhoneNumber) {
            return $value->phoneNumber;
        }

        throw ConversionException::conversionFailedInvalidType($value, $this->getName(), ['null', 'string', 'PhoneNumber']);
    }

    public function getName(): string
    {
        return self::MYTYPE;
    }
}
