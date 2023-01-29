# PHPNext

A PHP routing system for [Next.js static HTML](https://nextjs.org/docs/advanced-features/static-html-export) without any Node.js in back-end

## Installation

```bash
composer require gaucho/phpnext
```

## .htaccess

```
<IfModule mod_rewrite.c>
RewriteEngine On
RewriteBase /
RewriteRule ^index\.php$ - [L]
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule . /index.php [L]
</IfModule>
```

## Use

```php
<?php
require 'vendor/autoload.php';

use gaucho\PHPNext;

$outDir=realpath(__DIR__.'/out');
$pn=new PHPNext($outDir);
$pn->print();
```

