<?php

declare(strict_types=1);

namespace Bytexr\QueueableBulkActions\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Bytexr\QueueableBulkActions\QueueableBulkActions
 */
class QueueableBulkActions extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \Bytexr\QueueableBulkActions\QueueableBulkActions::class;
    }
}
