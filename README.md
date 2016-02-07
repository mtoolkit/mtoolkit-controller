MToolkit - Controller
=====================
The controller module of [MToolkit](https://github.com/mtoolkit/mtoolkit) framework.

# Summary
- [Intro](#intro)
- [How a controller works](#how_a_controller_works)
- [Routing](#routing)
- [View Life Cycle](#view_life_cycle)

# How a controller works

## MPageController

MPageController is an autorun controller for the web pages.

Controller (Index.php):

```php
<?php

require_once __DIR__ . '/Settings.php';

use \MToolkit\Controller\MPageController;

class Index extends MAbstractPageController
{
    private $masterPage;

    public function __construct()
    {
        parent::__construct(__DIR__.'/Index.view');
    }

    public function helloWorld()
    {
        return "Hello World";
    }
} 
```

And the *view* file. Every view file must contain the meta tag, with the correct *content-type*:
```html
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
```
*Index.view*:

```php
<?php /* @var $this Index */ ?>
<html>
    <head>
        <title>Entry page</title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    </head>
    <body>
        <b><?php echo $this->helloWorld(); ?></b>
    </body>
</html>
```

And now you can create your web app.

## Handler

# Routing

# View Life Cycle
0. Construct
1. Init
2. Load
3. Pre render
4. Render
5. Post render

## Construct
## Init
## Load
## Pre render
## Render
## Post render


