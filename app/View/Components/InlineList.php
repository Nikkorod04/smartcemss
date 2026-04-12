<?php

namespace App\View\Components;

use Illuminate\View\Component;

class InlineList extends Component
{
    public $items;
    public $separator;

    public function __construct($items = [], $separator = ', ')
    {
        // Handle both JSON-encoded strings and arrays
        if (is_string($items)) {
            $this->items = json_decode($items, true) ?? [];
        } else {
            $this->items = (array) $items;
        }
        
        $this->separator = $separator;
    }

    public function render()
    {
        $text = implode($this->separator, array_filter($this->items));
        return view('components.inline-list', ['text' => $text]);
    }
}
