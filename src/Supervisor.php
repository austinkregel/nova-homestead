<?php

namespace Kregel\Supervisor;

use Kregel\Supervisor\Nova\Cards\Help;
use Laravel\Nova\Nova;
use Laravel\Nova\Tool;

class Supervisor extends Tool
{
    /**
     * Perform any tasks that need to happen when the tool is booted.
     *
     * @return void
     */
    public function boot()
    {
        Nova::script('supervisor', __DIR__.'/../dist/js/tool.js');
        Nova::style('supervisor', __DIR__.'/../dist/css/tool.css');
    }

    /**
     * Build the view that renders the navigation links for the tool.
     *
     * @return \Illuminate\View\View
     */
    public function renderNavigation()
    {
        return view('supervisor::navigation');
    }
}
