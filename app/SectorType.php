<?php

namespace App;

use Filament\Support\Contracts\HasLabel;

enum SectorType: string implements HasLabel
{
    case Urbano = 'urbano';
    case Rural = 'rural';
    case Other = 'other';

    public function getLabel(): ?string
    {
        return $this->name;
    }
}
