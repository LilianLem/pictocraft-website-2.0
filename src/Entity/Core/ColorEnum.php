<?php

namespace App\Entity\Core;

enum ColorEnum: string
{
    case PRIMARY = "primary";
    case SECONDARY = "secondary";
    case SUCCESS = "success";
    case DANGER = "danger";
    case WARNING = "warning";
    case INFO = "info";
    case LIGHT = "light";
    case DARK = "dark";
    case LINK = "link";
}
