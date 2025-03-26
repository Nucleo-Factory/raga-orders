<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class breadcrumb extends Component
{
    public $segments = [];
    public $currentPath = '';

    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        $this->currentPath = request()->path();
        if ($this->currentPath !== '/') {
            $this->segments = collect(explode('/', $this->currentPath))
                ->filter()
                ->map(function ($segment) {
                    return [
                        'name' => ucfirst(str_replace(['-', '_'], ' ', $segment)),
                        'url' => $segment
                    ];
                })
                ->values()
                ->toArray();
        }
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.breadcrumb');
    }
}
