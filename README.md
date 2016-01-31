MToolkit - Controller
=====================
The controller module of [MToolkit](https://github.com/mtoolkit/mtoolkit) framework.

# Summary
- [Intro](#intro)
- [View Life Cycle](#view_life_cycle)
- [Handler](#handler)
- [Routing](#routing)

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

# Handler

# Routing
## Friendly URL

Create a simple .htaccess file on your root directory if you're using Apache with mod_rewrite enabled.
```apache
Options +FollowSymLinks
RewriteEngine On
RewriteRule ^(.*)$ index.php [NC,L]
```
