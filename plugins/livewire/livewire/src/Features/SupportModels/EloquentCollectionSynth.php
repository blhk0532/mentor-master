<?php

declare(strict_types=1);

namespace Livewire\Features\SupportModels;

use Exception;
use Illuminate\Database\ClassMorphViolationException;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Queue\SerializesAndRestoresModelIdentifiers;
use Livewire\Mechanisms\HandleComponents\Synthesizers\Synth;

class EloquentCollectionSynth extends Synth
{
    use IsLazy, SerializesAndRestoresModelIdentifiers;

    public static $key = 'elcln';

    public static function match($target)
    {
        return $target instanceof EloquentCollection;
    }

    public function dehydrate(EloquentCollection $target, $dehydrateChild)
    {
        if ($this->isLazy($target)) {
            $meta = $this->getLazyMeta($target);

            return [
                null,
                $meta,
            ];
        }

        $class = $target::class;
        $modelClass = $target->getQueueableClass();

        /**
         * `getQueueableClass` above checks all models are the same and
         * then returns the class. We then instantiate a model object
         * so we can call `getMorphClass()` on it.
         *
         * If no alias is found, this just returns the class name
         */
        if ($modelClass) {
            try {
                $modelAlias = (new $modelClass)->getMorphClass();
            } catch (ClassMorphViolationException $e) {
                // If the model is not using morph classes, this exception is thrown
                $modelAlias = $modelClass;
            }
        } else {
            $modelAlias = null;
        }

        $meta = [];

        $serializedCollection = (array) $this->getSerializedPropertyValue($target);

        $meta['keys'] = $serializedCollection['id'];
        $meta['class'] = $class;
        $meta['modelClass'] = $modelAlias;

        return [
            null,
            $meta,
        ];
    }

    public function hydrate($data, $meta, $hydrateChild)
    {
        $class = $meta['class'];

        $modelClass = $meta['modelClass'];

        // If no alias found, this returns `null`
        $modelAlias = Relation::getMorphedModel($modelClass);

        if (! is_null($modelAlias)) {
            $modelClass = $modelAlias;
        }

        $keys = $meta['keys'] ?? [];

        if (count($keys) === 0) {
            return new $class;
        }

        return $this->makeLazyProxy($class, $meta, function () use ($modelClass, $keys, $meta) {
            // We are using Laravel's method here for restoring the collection, which ensures
            // that all models in the collection are restored in one query, preventing n+1
            // issues and also only restores models that exist.
            $collection = (new $modelClass)->newQueryForRestoration($keys)->useWritePdo()->get();

            $collection = $collection->keyBy->getKey();

            return new $meta['class'](
                collect($meta['keys'])->map(function ($id) use ($collection) {
                    return $collection[$id] ?? null;
                })->filter()
            );
        });
    }

    public function get(&$target, $key)
    {
        throw new Exception('Can\'t access model properties directly');
    }

    public function set(&$target, $key, $value, $pathThusFar, $fullPath)
    {
        throw new Exception('Can\'t set model properties directly');
    }

    public function call($target, $method, $params, $addEffect)
    {
        throw new Exception('Can\'t call model methods directly');
    }
}
