<?php

// src/Enum/Unit.php
namespace App\Enum;

use Symfony\Contracts\Translation\TranslatableInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

enum Unit: string implements TranslatableInterface
{
    case GRAM = 'g';
    case MILLILITER = 'ml';
    case KILOGRAM = 'kg';
    case LITER = 'l';
    case PIECE = 'pc';
    case TEASPOON = 'tsp';
    case TABLESPOON = 'tbsp';
    case PINCH = 'pinch';

    public function trans(
        TranslatorInterface $translator,
        ?string $locale = null
    ): string {
        $key = match ($this) {
            self::GRAM => 'unit.gram',
            self::MILLILITER => 'unit.milliliter',
            self::KILOGRAM => 'unit.kilogram',
            self::LITER => 'unit.liter',
            self::PIECE => 'unit.piece',
            self::TEASPOON => 'unit.teaspoon',
            self::TABLESPOON => 'unit.tablespoon',
            self::PINCH => 'unit.pinch',
        };

        return $translator->trans(
            $key,
            domain: 'admin',
            locale: $locale
        );
    }
}
