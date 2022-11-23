<?php

namespace App\Doctrine\Type\Modules\Survey;

use App\Doctrine\Type\EnumType;
use App\Entity\Modules\Survey\QuestionFieldTypeEnum;

class QuestionFieldTypeEnumType extends EnumType
{
    public function getEnum(): string
    {
        return QuestionFieldTypeEnum::class;
    }

    public function getName(): string
    {
        return "question_field_type_enum";
    }
}