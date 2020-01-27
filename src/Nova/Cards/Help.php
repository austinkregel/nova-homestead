<?php

namespace Kregel\Supervisor\Nova\Cards;

use Laravel\Nova\Card;

class Help extends Card
{
    public $width = 'full';

    public function component()
    {
        return 'supervisor-help';
    }
}
