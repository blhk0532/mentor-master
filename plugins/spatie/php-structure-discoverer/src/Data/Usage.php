<?php

declare(strict_types=1);

namespace Spatie\StructureDiscoverer\Data;

class Usage
{
    public function __construct(
        public string $fcqn,
        public ?string $name = null,
    ) {
        $this->name ??= $this->resolveNonFcqnName($this->fcqn);
    }

    public function resolveNonFcqnName(string $fcqn): string
    {
        $position = mb_strrpos($fcqn, '\\');

        if ($position === false) {
            return $fcqn;
        }

        return mb_substr($fcqn, $position + mb_strlen('\\'));
    }
}
