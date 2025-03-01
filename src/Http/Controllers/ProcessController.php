<?php

declare(strict_types=1);

namespace Laravelwebdev\Filepond\Http\Controllers;

use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Nova;
use Laravelwebdev\Filepond\Data\Data;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class ProcessController extends BaseController
{
    use ValidatesRequests;

    /**
     * @throws BindingResolutionException
     */
    public function __invoke(NovaRequest $request): Response
    {
        $attribute = $request->input('attribute');
        $resourceName = $request->input('resourceName');
        $file = $request->file($attribute);
        if (is_array($file)) {
            foreach ($file as $f) {
                if (! $f->isValid()) {
                    return response()->json(
                        data: [$attribute => [$f->getErrorMessage()]],
                        status: 500,
                    );
                }
            }
        } else {
            if (! $file->isValid()) {

            return response()->json(
                data: [$attribute => [$file->getErrorMessage()]],
                status: 500,
            );

        }
    }

        try {

            $resource = Nova::resourceInstanceForKey($resourceName);
            $field = $resource
                ->creationFields($request)
                ->firstWhere('attribute', $attribute);
            $rules = $field ? $field->getCreationRules($request) : [];

            $this->validate($request, Arr::only($rules, $attribute));

        } catch (ValidationException $exception) {

            return response()->json(
                data: $exception->errors(),
                status: $exception->status,
            );

        } catch (Throwable $exception) {

            return response()->json(
                data: [$attribute => [$exception->getMessage()]],
                status: 500,
            );

        }

        $fileName = $file->getClientOriginalName();
        $location = sprintf('%s/%s', config('filepond.temp_path'), Str::random());

        if (! $path = $file->storeAs($location, $fileName, ['disk' => config('filepond.temp_disk')])) {

            return response()->json(
                data: [$attribute => [__('Could not save file.')]],
                status: 500,
            );

        }

        return response()->make(
            Data::make($path, $fileName, config('filepond.temp_disk'))->encrypt(),
        );
    }
}
