<?php

declare(strict_types=1);

namespace Laravelwebdev\Filepond\Http\Controllers;

use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Http\Response;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravelwebdev\Filepond\Data\Data;

class RevertController
{
    /**
     * @throws BindingResolutionException
     */
    public function __invoke(NovaRequest $request): Response
    {
        if (Data::fromEncrypted($request->getContent())->deleteDirectory()) {
            return response()->make();
        }

        return response()->setStatusCode(500);
    }
}
