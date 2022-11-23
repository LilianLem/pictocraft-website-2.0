<?php

namespace App\Entity\Modules\Survey;

// https://symfony.com/doc/current/reference/forms/types.html
enum QuestionFieldTypeEnum: string
{
    // Text Fields
    case TEXT = "TextType";
    case TEXTAREA = "TextareaType";
    case EMAIL = "EmailType";
    case INTEGER = "IntegerType";
    case MONEY = "MoneyType";
    case NUMBER = "NumberType";
    case PASSWORD = "PasswordType";
    case PERCENT = "PercentType";
    case SEARCH = "SearchType";
    case URL = "UrlType";
    case RANGE = "RangeType";
    case TEL = "TelType";
    case COLOR = "ColorType";

    // Choice Fields
    case CHOICE = "ChoiceType";
    case ENUM = "EnumType";
    case ENTITY = "EntityType";
    case COUNTRY = "CountryType";
    case LANGUAGE = "LanguageType";
    case LOCALE = "LocaleType";
    case TIMEZONE = "TimezoneType";
    case CURRENCY = "CurrencyType";

    // Date and Time Fields
    case DATE = "DateType";
    case DATE_INTERVAL = "DateIntervalType";
    case DATE_TIME = "DateTimeType";
    case TIME = "TimeType";
    case BIRTHDAY = "BirthdayType";
    case WEEK = "WeekType";

    // Other Fields
    case CHECKBOX = "CheckboxType";
    case FILE = "FileType";
    case RADIO = "RadioType";

    // UID Fields
    case UUID = "UuidType";
    case ULID = "UlidType";

    // Field Groups
    case COLLECTION = "CollectionType";
    case REPEATED = "RepeatedType";

    // Hidden Fields
    case HIDDEN = "HiddenType";

    // Buttons
    case BUTTON = "ButtonType";
    case RESET = "ResetType";
    case SUBMIT = "SubmitType";

    // Base Fields
    case FORM = "FormType";
}
