<?php

namespace App\Doctrine\Type;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;
use StringBackedEnum;

abstract class EnumType extends Type
{
    // Source : https://knplabs.com/en/blog/how-to-map-a-php-enum-with-doctrine-in-a-symfony-project
    // TODO : problème sur les migrations down() lorsque les éléments de l'enum sont modifiés. Il faut rectifier la migration manuellement pour le moment

    /**
     * @return class-string
     */
    abstract public function getEnum(): string;

    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        /** @var StringBackedEnum $enum */
        $enum = $this->getEnum();
        $cases = array_map(
            fn ($enumItem) => "'".str_replace("'", "\\'", $enumItem->value)."'", $enum::cases()
        );

        return sprintf("ENUM(%s)", implode(", ", $cases));
    }

    public function requiresSQLCommentHint(AbstractPlatform $platform): bool
    {
        return true;
    }

    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        if(is_null($value)) return null;

        /** @var StringBackedEnum $enumClass */
        $enumClass = $this->getEnum();

        return $enumClass::from($value);
    }

    public function convertToDatabaseValue($enum, AbstractPlatform $platform)
    {
        return is_null($enum) ? null : $enum->value;
    }
}
