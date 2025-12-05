<?php

declare(strict_types=1);

namespace App\Enum;

enum FeatureFlag: string
{
    case EnableApplicativeCache = 'enable_applicative_cache';
    case EnableHttpCache = 'enable_http_cache';
}