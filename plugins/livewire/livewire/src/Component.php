<?php

declare(strict_types=1);

namespace Livewire;

use BadMethodCallException;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Traits\Macroable;
use Livewire\Concerns\InteractsWithProperties;
use Livewire\Exceptions\PropertyNotFoundException;
use Livewire\Features\SupportAttributes\HandlesAttributes;
use Livewire\Features\SupportDisablingBackButtonCache\HandlesDisablingBackButtonCache;
use Livewire\Features\SupportEvents\HandlesEvents;
use Livewire\Features\SupportFormObjects\HandlesFormObjects;
use Livewire\Features\SupportHtmlAttributeForwarding\HandlesHtmlAttributeForwarding;
use Livewire\Features\SupportIslands\HandlesIslands;
use Livewire\Features\SupportJsEvaluation\HandlesJsEvaluation;
use Livewire\Features\SupportPageComponents\HandlesPageComponents;
use Livewire\Features\SupportRedirects\HandlesRedirects;
use Livewire\Features\SupportReleaseTokens\HandlesReleaseTokens;
use Livewire\Features\SupportSlots\HandlesSlots;
use Livewire\Features\SupportStreaming\HandlesStreaming;
use Livewire\Features\SupportTransitions\HandlesTransitions;
use Livewire\Features\SupportValidation\HandlesValidation;

abstract class Component
{
    use AuthorizesRequests;
    use HandlesAttributes;
    use HandlesDisablingBackButtonCache;
    use HandlesEvents;
    use HandlesFormObjects;
    use HandlesHtmlAttributeForwarding;
    use HandlesIslands;
    use HandlesJsEvaluation;
    use HandlesPageComponents;
    use HandlesRedirects;
    use HandlesReleaseTokens;
    use HandlesSlots;
    use HandlesStreaming;
    use HandlesTransitions;
    use HandlesValidation;
    use InteractsWithProperties;
    use Macroable { __call as macroCall; }

    protected $__id;

    protected $__name;

    public function __isset($property)
    {
        try {
            $value = $this->__get($property);

            if (isset($value)) {
                return true;
            }
        } catch (PropertyNotFoundException $ex) {
        }

        return false;
    }

    public function __get($property)
    {
        $value = 'noneset';

        $returnValue = function ($newValue) use (&$value) {
            $value = $newValue;
        };

        $finish = trigger('__get', $this, $property, $returnValue);

        $value = $finish($value);

        if ($value === 'noneset') {
            throw new PropertyNotFoundException($property, $this->getName());
        }

        return $value;
    }

    public function __unset($property)
    {
        trigger('__unset', $this, $property);
    }

    public function __call($method, $params)
    {
        $value = 'noneset';

        $returnValue = function ($newValue) use (&$value) {
            $value = $newValue;
        };

        $finish = trigger('__call', $this, $method, $params, $returnValue);

        $value = $finish($value);

        if ($value !== 'noneset') {
            return $value;
        }

        if (static::hasMacro($method)) {
            return $this->macroCall($method, $params);
        }

        throw new BadMethodCallException(sprintf(
            'Method %s::%s does not exist.', static::class, $method
        ));
    }

    final public function id()
    {
        return $this->getId();
    }

    final public function setId($id)
    {
        $this->__id = $id;
    }

    final public function getId()
    {
        return $this->__id;
    }

    final public function setName($name)
    {
        $this->__name = $name;
    }

    final public function getName()
    {
        return $this->__name;
    }

    final public function renderless()
    {
        $this->skipRender();
    }

    final public function skipRender($html = null)
    {
        if (store($this)->has('forceRender')) {
            return;
        }

        store($this)->set('skipRender', $html ?: true);
    }

    final public function forceRender()
    {
        store($this)->set('forceRender', true);
    }

    final public function skipMount()
    {
        store($this)->set('skipMount', true);
    }

    final public function skipHydrate()
    {
        store($this)->set('skipHydrate', true);
    }

    final public function hasProvidedView()
    {
        return method_exists($this, 'view');
    }

    final public function getProvidedView()
    {
        return $this->view();
    }

    final public function tap($callback)
    {
        $callback($this);

        return $this;
    }
}
