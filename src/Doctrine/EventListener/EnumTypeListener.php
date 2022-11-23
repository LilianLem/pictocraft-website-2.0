<?php

namespace App\Doctrine\EventListener;

use App\Doctrine\Type\EnumType;
use Doctrine\DBAL\Schema\Column;
use Doctrine\ORM\Tools\Event\GenerateSchemaEventArgs;

class EnumTypeListener
{
    // Source : https://knplabs.com/en/blog/how-to-map-a-php-enum-with-doctrine-in-a-symfony-project

    public function postGenerateSchema(GenerateSchemaEventArgs $eventArgs): void
    {
        $columns = [];

        foreach ($eventArgs->getSchema()->getTables() as $table) {
            foreach ($table->getColumns() as $column) {
                if ($column->getType() instanceof EnumType) {
                    $columns[] = $column;
                }
            }
        }

        /** @var Column $column */
        foreach ($columns as $column) {
            /** @var EnumType $type */
            $type = $column->getType();
            $enum = $type->getEnum();

            $cases = array_map(
                fn($enumItem) => "'".str_replace("'", "\\'", $enumItem->value)."'",
                $enum::cases()
            );

            $hash = md5(implode(',', $cases));

            $column->setComment(trim(sprintf(
                '%s (DC2Enum:%s)',
                $column->getComment(),
                $hash
            )));
        }
    }
}
