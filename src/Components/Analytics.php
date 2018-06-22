<?php

namespace Agencms\Core\Components;

use Illuminate\Http\Request;
use Agencms\Settings\Settings;
use Illuminate\Contracts\Support\Htmlable;

class Analytics implements Htmlable
{
    public function toHtml(): string
    {
        return view('agencms::components.analytics', [
            'ga_code' => Settings::get('global', 'ga_code', ''),
        ]);
    }
}
