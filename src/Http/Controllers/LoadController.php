<?php

declare(strict_types=1);

namespace Laravelwebdev\Filepond\Http\Controllers;

use Laravel\Nova\Http\Requests\NovaRequest;
use Laravelwebdev\Filepond\Data\Data;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class LoadController
{
    public function __invoke(NovaRequest $request): BinaryFileResponse
    {
        $data = Data::fromEncrypted($request->input('serverId'));

        return response()->file(
            file: $data->absolutePath(),
            headers: [
                'Content-Disposition' => sprintf('inline; filename="%s"', basename($data->filename)),
            ],
        );
    }
}
