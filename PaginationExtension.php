<?php

namespace Carew\Plugin\Pagination;

use Carew\ExtensionInterface;
use Carew\Carew;

class PaginationExtension implements ExtensionInterface
{
    public function register(Carew $carew)
    {
        $config = $carew->getContainer()->offsetGet('config');

        $maxPerPage = 10;
        if (isset($config['pagination']) && isset($config['pagination']['max_per_page'])) {
            $maxPerPage = $config['pagination']['max_per_page'];
            if (!is_int($maxPerPage)) {
                throw new \InvalidArgumentException('The pagination:max_per_page is not an int in your config.yml');
            }
        }

        $carew->getEventDispatcher()->addSubscriber(new PaginationListener($carew->getContainer()->offsetGet('twig'), $maxPerPage));
    }
}
