<?php

declare(strict_types=1);

namespace Livewire\Features\SupportStreaming;

class StreamManager
{
    private $component;

    private $hook;

    private $name;

    private $el;

    private $ref;

    private $content;

    private $replace;

    public function __construct($component, $hook)
    {
        $this->component = $component;
        $this->hook = $hook;
    }

    public function content($content, $replace = false)
    {
        $this->content = $content;
        $this->replace = $replace;

        return $this;
    }

    public function to($name = null, $el = null, $ref = null)
    {
        $this->name = $name;
        $this->el = $el;
        $this->ref = $ref;

        $this->hook->stream($this->content, $this->replace, $this->name, $this->el, $this->ref);

        return $this;
    }
}
