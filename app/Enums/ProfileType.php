<?php

namespace App\Enums;

enum ProfileType: int
{
    case Default = 0;
    case Unlisted = 99;

    public function label(): string
    {
        return match($this) {
            ProfileType::Default => 'Default',
            ProfileType::Unlisted => 'Unlisted',
        };
    }
}