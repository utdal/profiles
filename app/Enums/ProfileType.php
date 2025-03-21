<?php

namespace App\Enums;

enum ProfileType: int
{
    case Default = 0;
    case Unlisted = 90;
    case InMemoriam = 99;

    public function label(): string
    {
        return match($this) {
            ProfileType::Default => 'Default',
            ProfileType::Unlisted => 'Unlisted',
            ProfileType::InMemoriam => 'In Memoriam',
        };
    }
}