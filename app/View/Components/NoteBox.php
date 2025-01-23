<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class NoteBox extends Component
{
    public $moyenne;
    public $label;
    public bool $up;
    public bool $down;

    /**
     * Create a new component instance.
     */
    public function __construct($moyenne = '0', $label = 'Moyenne pondérée', bool $up = true, bool $down = true)
    {
        $this->moyenne = $moyenne;
        $this->label = $label;
        $this->up = $up;
        $this->down = $down; 
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.note-box');
    }
}
