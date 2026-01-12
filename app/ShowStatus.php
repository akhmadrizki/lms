<?php

namespace App;

enum ShowStatus: string
{
    case GLOBAL = 'global';
    case FIRM_ONLY = 'firm_only';
    case COMPANY_ONLY = 'company_only';
    case RESTRICTED = 'restricted';

    public function label(): string
    {
        return match ($this) {
            self::GLOBAL => 'Global',
            self::FIRM_ONLY => 'Firm Only',
            self::COMPANY_ONLY => 'Company Only',
            self::RESTRICTED => 'Restricted',
        };
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public static function labels(): array
    {
        return array_combine(self::values(), array_map(fn ($status) => $status->label(), self::cases()));
    }
}
