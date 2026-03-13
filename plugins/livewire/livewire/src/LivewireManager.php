<?php

declare(strict_types=1);

namespace Livewire;

use Closure;
use Livewire\Features\SupportAutoInjectedAssets\SupportAutoInjectedAssets;
use Livewire\Features\SupportLazyLoading\SupportLazyLoading;
use Livewire\Features\SupportTesting\DuskTestable;
use Livewire\Features\SupportTesting\Testable;
use Livewire\Mechanisms\ExtendBlade\ExtendBlade;
use Livewire\Mechanisms\FrontendAssets\FrontendAssets;
use Livewire\Mechanisms\HandleComponents\ComponentContext;
use Livewire\Mechanisms\HandleComponents\HandleComponents;
use Livewire\Mechanisms\HandleRequests\HandleRequests;
use Livewire\Mechanisms\PersistentMiddleware\PersistentMiddleware;

class LivewireManager
{
    public static $v4 = true;

    private LivewireServiceProvider $provider;

    private $queryParamsForTesting = [];

    private $cookiesForTesting = [];

    private $headersForTesting = [];

    public function setProvider(LivewireServiceProvider $provider)
    {
        $this->provider = $provider;
    }

    public function provide($callback)
    {
        Closure::bind($callback, $this->provider, $this->provider::class)();
    }

    public function component($name, $class = null)
    {
        $this->addComponent($name, class: $class);
    }

    public function addComponent($name, $viewPath = null, $class = null)
    {
        app('livewire.finder')->addComponent($name, class: $class, viewPath: $viewPath);
    }

    public function addLocation($viewPath = null, $classNamespace = null)
    {
        return app('livewire.finder')->addLocation(classNamespace: $classNamespace, viewPath: $viewPath);
    }

    public function addNamespace($namespace, $viewPath = null, $classNamespace = null, $classPath = null, $classViewPath = null)
    {
        return app('livewire.finder')->addNamespace($namespace, classNamespace: $classNamespace, viewPath: $viewPath, classPath: $classPath, classViewPath: $classViewPath);
    }

    public function componentHook($hook)
    {
        ComponentHookRegistry::register($hook);
    }

    public function propertySynthesizer($synth)
    {
        app(HandleComponents::class)->registerPropertySynthesizer($synth);
    }

    public function directive($name, $callback)
    {
        app(ExtendBlade::class)->livewireOnlyDirective($name, $callback);
    }

    public function precompiler($callback)
    {
        app(ExtendBlade::class)->livewireOnlyPrecompiler($callback);
    }

    public function prepareViewsForCompilationUsing(callable $callback)
    {
        app('livewire.compiler')->prepareViewsForCompilationUsing($callback);
    }

    public function new($name, $id = null)
    {
        return app('livewire.factory')->create($name, $id);
    }

    /**
     * @deprecated This method will be removed in a future version. Use exists() instead.
     */
    public function isDiscoverable($componentNameOrClass)
    {
        return $this->exists($componentNameOrClass);
    }

    public function exists($componentNameOrClass)
    {
        return app('livewire.factory')->exists($componentNameOrClass);
    }

    public function resolveMissingComponent($resolver)
    {
        return app('livewire.factory')->resolveMissingComponent($resolver);
    }

    public function mount($name, $params = [], $key = null, $slots = [])
    {
        return app(HandleComponents::class)->mount($name, $params, $key, $slots);
    }

    public function snapshot($component, $context = null)
    {
        return app(HandleComponents::class)->snapshot($component, $context);
    }

    public function fromSnapshot($snapshot)
    {
        return app(HandleComponents::class)->fromSnapshot($snapshot);
    }

    public function listen($eventName, $callback)
    {
        return on($eventName, $callback);
    }

    public function current()
    {
        return last(app(HandleComponents::class)::$componentStack);
    }

    public function findSynth($keyOrTarget, $component)
    {
        return app(HandleComponents::class)->findSynth($keyOrTarget, $component);
    }

    public function update($snapshot, $diff, $calls)
    {
        return app(HandleComponents::class)->update($snapshot, $diff, $calls);
    }

    public function updateProperty($component, $path, $value)
    {
        $dummyContext = new ComponentContext($component, false);

        $updatedHook = app(HandleComponents::class)->updateProperty($component, $path, $value, $dummyContext);

        $updatedHook();
    }

    public function isLivewireRequest()
    {
        return app(HandleRequests::class)->isLivewireRequest();
    }

    public function componentHasBeenRendered()
    {
        return SupportAutoInjectedAssets::$hasRenderedAComponentThisRequest;
    }

    public function forceAssetInjection()
    {
        SupportAutoInjectedAssets::$forceAssetInjection = true;
    }

    public function setUpdateRoute($callback)
    {
        return app(HandleRequests::class)->setUpdateRoute($callback);
    }

    public function getUriPrefix()
    {
        return app(HandleRequests::class)->getUriPrefix();
    }

    public function getUpdateUri()
    {
        return app(HandleRequests::class)->getUpdateUri();
    }

    public function setScriptRoute($callback)
    {
        return app(FrontendAssets::class)->setScriptRoute($callback);
    }

    public function useScriptTagAttributes($attributes)
    {
        return app(FrontendAssets::class)->useScriptTagAttributes($attributes);
    }

    public function withUrlParams($params)
    {
        return $this->withQueryParams($params);
    }

    public function withQueryParams($params)
    {
        $this->queryParamsForTesting = $params;

        return $this;
    }

    public function withCookie($name, $value)
    {
        $this->cookiesForTesting[$name] = $value;

        return $this;
    }

    public function withCookies($cookies)
    {
        $this->cookiesForTesting = array_merge($this->cookiesForTesting, $cookies);

        return $this;
    }

    public function withHeaders($headers)
    {
        $this->headersForTesting = array_merge($this->headersForTesting, $headers);

        return $this;
    }

    public function withoutLazyLoading()
    {
        SupportLazyLoading::disableWhileTesting();

        return $this;
    }

    /**
     * @template TComponent of \Livewire\Component
     *
     * @param  class-string<TComponent>|TComponent|string|array<array-key, Component>  $name
     * @param  array  $params
     * @return Testable<TComponent>
     */
    public function test($name, $params = [])
    {
        return Testable::create(
            $name,
            $params,
            $this->queryParamsForTesting,
            $this->cookiesForTesting,
            $this->headersForTesting,
        );
    }

    public function visit($name, $args = [])
    {
        if (class_exists(\Pest\Browser\Api\Livewire::class)) {
            return \Pest\Browser\Api\Livewire::test($name, $args);
        }

        return DuskTestable::create($name, $params = [], $this->queryParamsForTesting);
    }

    public function actingAs(\Illuminate\Contracts\Auth\Authenticatable $user, $driver = null)
    {
        Testable::actingAs($user, $driver);

        return $this;
    }

    public function isRunningServerless()
    {
        return in_array($_ENV['SERVER_SOFTWARE'] ?? null, [
            'vapor',
            'bref',
        ]);
    }

    public function addPersistentMiddleware($middleware)
    {
        app(PersistentMiddleware::class)->addPersistentMiddleware($middleware);
    }

    public function setPersistentMiddleware($middleware)
    {
        app(PersistentMiddleware::class)->setPersistentMiddleware($middleware);
    }

    public function getPersistentMiddleware()
    {
        return app(PersistentMiddleware::class)->getPersistentMiddleware();
    }

    public function zap()
    {
        return app('livewire.zap');
    }

    public function flushState()
    {
        trigger('flush-state');
    }

    public function originalUrl()
    {
        if ($this->isLivewireRequest()) {
            return url()->to($this->originalPath());
        }

        return url()->current();
    }

    public function originalPath()
    {
        if ($this->isLivewireRequest()) {
            $snapshot = json_decode(request('components.0.snapshot'), true);

            return data_get($snapshot, 'memo.path', 'POST');
        }

        return request()->path();
    }

    public function originalMethod()
    {
        if ($this->isLivewireRequest()) {
            $snapshot = json_decode(request('components.0.snapshot'), true);

            return data_get($snapshot, 'memo.method', 'POST');
        }

        return request()->method();
    }

    public function isCspSafe()
    {
        return config('livewire.csp_safe', false);
    }
}
