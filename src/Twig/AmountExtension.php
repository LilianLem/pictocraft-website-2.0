<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class AmountExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            new TwigFilter("amount", [$this, "amount"])
        ];
    }

    public function amount(
        int $value,
        string $currencySymbol = "€",
        string $decimalSeparator = ",",
        string $thousandsSeparator = " ",
        CurrencySymbolPositionEnum $currencySymbolPosition = CurrencySymbolPositionEnum::AFTER,
        bool $displaySpaceBetweenAmountAndCurrencySymbol = true
    ): string {
        $price = number_format($value / 100, 2, $decimalSeparator, $thousandsSeparator);

        $priceParts = [$price, $displaySpaceBetweenAmountAndCurrencySymbol ? " " : "", $currencySymbol];

        if($currencySymbolPosition === CurrencySymbolPositionEnum::BEFORE) $priceParts = array_reverse($priceParts);

        return implode($priceParts);
    }
}