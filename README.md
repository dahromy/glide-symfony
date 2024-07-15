# DahRomyGlideBundle

DahRomyGlideBundle is a Symfony bundle that integrates the [Glide](https://glide.thephpleague.com/) image manipulation library into your Symfony application. It provides an easy way to manipulate and serve images on-the-fly.

For more information about Glide and its capabilities, please refer to the [official Glide documentation](https://glide.thephpleague.com/).

## Requirements

- PHP 7.4 or higher
- Symfony 5.0 or higher
- GD Library or Imagick PHP extension

## Installation

You can install the bundle using Composer:

```bash
composer require dahromy/glide-symfony
```

## Configuration

After installing the bundle, you need to configure it in your `config/packages/glide.yaml` file. Here's an example configuration with default values:

```yaml
glide:
    source: '%kernel.project_dir%/public/images'
    cache: '%kernel.project_dir%/public/cache'
    driver: gd # Options: gd, imagick
    defaults:
        q: 90
        fm: 'auto'
    presets:
        small:
            w: 200
            h: 200
            fit: crop
        medium:
            w: 600
            h: 400
            fit: crop
```

## Usage

### In Twig Templates

You can use the `glide_asset` Twig function or the `glide` filter to generate URLs for your images:

1. Using the `glide_asset` function:

```twig
<img src="{{ glide_asset('path/to/image.jpg', {w: 300, h: 200}) }}" alt="My Image">
```

Or use a preset:

```twig
<img src="{{ glide_asset('path/to/image.jpg', {}, 'small') }}" alt="My Image">
```

2. Using the `glide` filter:

```twig
<img src="{{ 'path/to/image.jpg'|glide({w: 300, h: 200}) }}" alt="My Image">
```

With a preset:

```twig
<img src="{{ 'path/to/image.jpg'|glide({}, 'small') }}" alt="My Image">
```

### In Controllers

You can use the `GlideService` in your controllers to generate image URLs or get image responses:

```php
use DahRomy\Glide\Service\GlideService;

class MyController extends AbstractController
{
    public function myAction(GlideService $glideService)
    {
        $imageUrl = $glideService->getImageUrl('path/to/image.jpg', ['w' => 300, 'h' => 200]);
        
        // Or get the image response directly
        $response = $glideService->getImageResponse('path/to/image.jpg', ['w' => 300, 'h' => 200]);
        
        // ...
    }
}
```

## Features

- On-the-fly image manipulation
- Preset configurations for common image sizes
- Secure image URLs with signing
- Easy integration with Twig templates and controllers

## Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

## License

This bundle is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
