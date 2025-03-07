<?php

declare(strict_types=1);

namespace CORS\Bundle\AdminerBundle\EventListener;

use Pimcore\Event\BundleManager\PathsEvent;
use Pimcore\Event\BundleManagerEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

final class AdminJsListener implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            BundleManagerEvents::JS_PATHS => 'getAdminJavascript',
            BundleManagerEvents::CSS_PATHS => 'getAdminCss',
        ];
    }

    public function getAdminJavascript(PathsEvent $event): void
    {
        $event->setPaths(array_merge($event->getPaths(), [
            '/bundles/corsadminer/pimcore/js/plugin.js',
        ]));
    }

    public function getAdminCss(PathsEvent $event): void
    {
        $event->setPaths(array_merge($event->getPaths(), [
            '/bundles/corsadminer/pimcore/css/adminer.css',
        ]));
    }
}
