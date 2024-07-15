# DahRomyGlideBundle

DahRomyGlideBundle is a Symfony bundle that integrates the Glide image manipulation library into your Symfony application. It provides an easy way to manipulate and serve images on-the-fly.

## Installation

You can install the bundle using Composer:

```bash
composer require dahromy/glide-bundle
```

## Configuration

After installing the bundle, you need to configure it in your `config/packages/dah_romy_glide.yaml` file:

```yaml
dah_romy_glide:
    source: '%kernel.project_dir%/public/images'
    cache: '%kernel.project_dir%/public/cache'
    base_url: '/glide'
    presets:
        small:
            w: 200
            h: 200
            fit: crop
        medium:
            w: 600
            h: 400
            fit: crop
    sign_key: 'your-secret-key'
```

## Usage

### In Twig Templates

You can use the `glide_asset` Twig function to generate URLs for your images:

```twig
<img src="{{ glide_asset('path/to/image.jpg', {w: 300, h: 200}) }}" alt="My Image">
```

Or use a preset:

```twig
<img src="{{ glide_asset('path/to/image.jpg', {}, 'small') }}" alt="My Image">
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