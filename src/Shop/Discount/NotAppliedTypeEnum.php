<?php

namespace App\Shop\Discount;

enum NotAppliedTypeEnum: string
{
    case SAFE = "Inoffensif";
    case CRITICAL = "Critique";
}
