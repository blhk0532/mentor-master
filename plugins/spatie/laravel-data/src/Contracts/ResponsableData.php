<?php

declare(strict_types=1);

namespace Spatie\LaravelData\Contracts;

use Illuminate\Contracts\Support\Responsable;

interface ResponsableData extends Responsable
{
    public static function allowedRequestIncludes(): ?array;

    public static function allowedRequestExcludes(): ?array;

    public static function allowedRequestOnly(): ?array;

    public static function allowedRequestExcept(): ?array;

    /**
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function toResponse($request);
}
