# GrabzitBundle

The `GrabzitBundle` integrates the [GrabzIt](https://github.com/GrabzIt/grabzit-php) PHP library with Symfony.

[![Latest Stable Version](https://poser.pugx.org/tlamedia/grabzit-bundle/v/stable)](https://packagist.org/packages/tlamedia/grabzit-bundle) 
[![Build Status](https://api.travis-ci.org/tlamedia/GrabzitBundle.svg?branch=master)](https://travis-ci.org/tlamedia/GrabzitBundle)
[![License](https://poser.pugx.org/tlamedia/grabzit-bundle/license)](https://packagist.org/packages/tlamedia/grabzit-bundle)


## Installation

Get the bundlle:

`composer require tlamedia/grabzit-bundle`

Then enable it in your kernel:

```php
// config/bundles.php
return [
    //...
    Tla\GrabzitBundle\TlaGrabzitBundle::class => ['all' => true],
    //...
];
```


## Configuration

Configure the API key to use:

```yaml
# config.yml
tla_grabzit:
    key: 'Your_Application_Key' # Replace with your own
    secret: 'Your_Application_Secret' # Replace with your own
```


## Usage

The bundle registers the services `tla_grabzit.imageoptions`, `tla_grabzit.pdfoptions`, `tla_grabzit.docxoptions`, `tla_grabzit.animationoptions`, `tla_grabzit.tableoptions` and `tla_grabzit.client`, which allows you to use the GrabzIt API.


### Request a thumbnail from a service

```php
namespace App\Service;

use Symfony\Component\DependencyInjection\ContainerInterface as Container;

class ThumbnailGenerator
{
    private $container;

    public function __construct(Container $container)
    {
        $this->router = $router;
        $this->container = $container;
    }

    public function generateThumbnail($domain)
    {
        $grabzItHandlerUrl = 'https://www.my-grabzit-thumbnail-site.com/api/thumbmail-ready';

        $options = $this->container->get('tla_grabzit.imageoptions');
        $options->setBrowserWidth(1024);
        $options->setBrowserHeight(768);
        $options->setFormat("png");
        $options->setWidth(320);
        $options->setHeight(240);
        $options->setCustomId($domain);

        $grabzIt = $this->container->get('tla_grabzit.client');
        $grabzIt->URLToImage("http://".$domain, $options);
        $grabzIt->Save($grabzItHandlerUrl);

        try {
            $grabzIt->URLToImage("http://".$domain, $options);
            $grabzIt->Save($grabzItHandlerUrl);
            $result = true;
        } catch (\Throwable $t) {
            $result = false;
        }

        return $result;
    }
}
```


### Recieve thumbnail with GrabzIt handler in a controller

```php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ApiController extends Controller
{
    public function thumbnailReadyAction(Request $request)
    {
        $id = urldecode($request->query->get('id'));
        $customId = $request->query->get('customid');
        $thumbnailFormat = $request->query->get('format');

        if ($id && $customId && $thumbnailFormat) {

            $grabzItApplicationKey = $this->container->getParameter('tla_grabzit.key');

            if (0 === strpos($id, $grabzItApplicationKey)) {

                $grabzIt = $this->container->get('tla_grabzit.client');
                $result = $grabzIt->GetResult($id);

                if ($result) {
                    $rootPath = $this->get('kernel')->getRootDir() . '/../';
                    $thumbnailsPath = $rootPath . 'var/thumbnails/';
                    $fileName = $customId. '.' .$thumbnailFormat;
                    
                    file_put_contents($thumbnailsPath . $fileName, $result);
                } else {
                    throw $this->createNotFoundException('GrabzIt did not return a file');
                }
            } else {
                throw $this->createNotFoundException('Wrong key - Unauthorized access');
            }
        } else {
            throw $this->createNotFoundException('Missing parameters');
        }
        return new Response(null, 200);
    }
}
```


## Documentation

Detailed documentation on how to access each API method can be found in the documentation on the
[GrabzIt website](https://grabz.it/api/php/)


## License

This package is available under the [MIT license](LICENSE).
