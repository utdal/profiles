<?php

namespace App\Enums;

enum ProfileSectionType: string
{
    case Default = 'default';
    case Publications = 'publications';
    case Appointments = 'appointments';
    case Awards = 'awards';
    case News = 'news';
    case Support = 'support';
    case Presentations = 'presentations';
    case Projects = 'projects';
    case Additionals = 'additionals';
    case Affiliations = 'affiliations';
    case Activities = 'activities';
    case Areas = 'areas';
    case Preparation = 'preparation';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public function perPage(): int
    {
        return match ($this) {
            self::Publications => 8,
            self::Appointments => 10,
            self::Awards => 10,
            self::Affiliations => 10,
            self::News => 5,
            self::Support => 5,
            self::Presentations => 5,
            self::Projects => 5,
            self::Additionals => 3,
            self::Activities => 6,
            self::Areas => 4,
            self::Preparation => 7,
            self::Default => 5,
        };
    }
}