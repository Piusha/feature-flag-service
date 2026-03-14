<?php

namespace App\Modules\FeatureFlags\Domain\Enums;

enum FeatureFlagType: string
{
    case BOOLEAN = 'boolean';
    case RULE_BASED = 'rule_based';
}
