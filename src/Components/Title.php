<?php

namespace Agencms\Core\Components;

use Illuminate\Http\Request;
use Agencms\Settings\Settings;
use Illuminate\Contracts\Support\Htmlable;

class Title implements Htmlable
{
    protected $title;

    public function __construct(Request $request, string $title = null)
    {
        $this->title = $title ?? Settings::get('global', 'title', config('app.name'));
    }

    public function toHtml(): string
    {
        return view('agencms::components.title', [
            'title' => trim(sprintf(
                '%s %s %s',
                Settings::get('global', 'title_prefix', ''),
                $this->title,
                Settings::get('global', 'title_suffix', '')
            )),
        ]);
    }
}
