<?php

namespace Kregel\Homestead;

use Kregel\Homestead\Nova\Cards\Help;
use Laravel\Nova\Nova;
use Laravel\Nova\Tool;

class Homestead extends Tool
{
    /**
     * Perform any tasks that need to happen when the tool is booted.
     *
     * @return void
     */
    public function boot()
    {
        Nova::script('homestead', __DIR__.'/../dist/js/tool.js');
        Nova::style('homestead', __DIR__.'/../dist/css/tool.css');
    }

    /**
     * Build the view that renders the navigation links for the tool.
     *
     * @return \Illuminate\View\View
     */
    public function renderNavigation()
    {
        return view('homestead::navigation');
    }
}
