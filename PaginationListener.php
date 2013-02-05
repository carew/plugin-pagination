<?php

namespace Carew\Plugin\Pagination;

use Carew\Event\Events;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class PaginationListener implements EventSubscriberInterface
{
    private $twig;
    private $maxPerPage;

    public function __construct(\Twig_Environment $twig, $maxPerPage = 10)
    {
        $this->twig       = $twig;
        $this->maxPerPage = $maxPerPage;
    }

    public function onDocument($event)
    {
        $indexes    = $event->getSubject();
        $indexesTmp = array();

        foreach ($indexes as $index) {
            if ('html' === pathinfo($index->getPath(),  PATHINFO_EXTENSION)) {
                $vars    = $index->getVars();
                $posts   = $vars['posts'];
                $nbPosts = count($posts);

                $this->twig->addGlobal('nb_posts', $nbPosts);

                if ($this->maxPerPage >= $nbPosts) {
                    return;
                }

                foreach (array_chunk($posts, $this->maxPerPage) as $page => $chunk) {
                    $newIndex = clone $index;
                    $varsTmp = array_replace($vars, array(
                        'posts' => $chunk,
                        'page'  => $page + 1,
                    ));
                    $newIndex->setVars($varsTmp);
                    if (0 === $page) {
                        $newIndex->setPath('index.html');
                    } else {
                        $newIndex->setPath(sprintf('page/%s.html', $page + 1));
                    }
                    $indexesTmp[$newIndex->getPath()] = $newIndex;
                }
            }
        }

        $this->twig->addGlobal('posts_pagination', $indexesTmp);

        $event->setSubject(array_merge($indexes, $indexesTmp));
    }

    public static function getSubscribedEvents()
    {
        return array(
            Events::INDEXES => array(
                array('onDocument', 1024),
            ),
        );
    }
}
