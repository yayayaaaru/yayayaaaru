<?php

declare(strict_types=1);

namespace App\Enums;

enum GameAgeRating: string
{
    case R0 = '0+';
    case R6 = '6+';
    case R12 = '12+';
    case R16 = '16+';
    case R18 = '18+';
}
