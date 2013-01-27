Pagination plugin for [Carew](http://github.com/lyrixx/Carew)
=============================================================

Installation
------------

Install it with composer:

```
composer require carew/plugin-pagination:dev-master
```

Then configure `config.yml`

```
engine:
    extensions:
        - Carew\Plugin\Pagination\PaginationExtension

pagination:
    max_per_page: 10
```
