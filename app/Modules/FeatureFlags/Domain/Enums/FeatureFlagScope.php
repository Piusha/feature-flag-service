<?php

namespace App\Modules\FeatureFlags\Domain\Enums;

enum FeatureFlagScope: string
{
    case COMPONENT = 'component';
    case FEATURE = 'feature';
    case PAGE = 'page';
}
