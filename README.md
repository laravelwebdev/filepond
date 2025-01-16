# Nova Filepond (Nova v5)

A Nova field for uploading File, Image and Video using [Filepond](https://github.com/pqina/filepond).

# Installation

You can install the package via composer:

```shell
composer require laravelwebdev/filepond
```

# Features

- Single/Multiple files upload
- Sortable files
- Preview images, videos and audio
- Enable / Disable preview
- Extends the original Laravel Nova File field giving you access to all the methods/functionality of the default file upload.
- Drag and drop files
- Paste files directly from the clipboard
- Store custom attributes (original file name, size, etc)
- Prunable files (Auto delete files when the model is deleted)
- Dark mode support

# Usage

The field extends the original Laravel Nova File field, so you can use all the methods available in the original field.

Basic usage:

```php
use Laravelwebdev\Filepond\Filepond;

class Post extends Resource
{
    public function fields(NovaRequest $request): array
    {
        return [
            Filepond::make('Images', 'images')
                ->rules('required')
                ->prunable()
                ->disablePreview()
                ->multiple() 
                ->limit(4),
        ];
    }
}
```

When uploading multiple files you will need to cast the attribute to an array in your model class

```php
class Post extends Model {
 
    protected $casts = [
        'images' => 'array'
    ];

}
```

You can also store original file name / size by using `storeOriginalName` and `storeOriginalSize` methods.

```php
use Laravelwebdev\Filepond\Filepond;

class Post extends Resource
{
    public function fields(NovaRequest $request): array
    {
        return [
            Filepond::make('Images', 'images')
                ->storeOriginalName('name')
                ->storeSize('size')
                ->multiple(),
            
            // or you can manually decide how to store the data
            // Note: the store method will be called for each file uploaded and the output will be stored into a single json column
            Filepond::make('Images', 'images')
                ->multiple()
                ->store(function (NovaRequest $request, Model $model, string $attribute): array {
                    return [
                        $attribute => $request->images->store('/', 's3'),
                        'name' => $request->images->getClientOriginalName(),
                        'size' => $request->images->getSize(),
                        'metadata' => '...'
                    ];
                })
        ];
    }
}
```
> Note when using `storeOriginalName` and `storeSize` methods, you will need to add the columns to your database table if you are in "single" file mode.

## ⭐️ Show Your Support

Please give a ⭐️ if this project helped you!

## License

The MIT License (MIT). Please see [License File](https://raw.githubusercontent.com/dcasia/nova-filepond/master/LICENSE) for more information.
