<?php

declare(strict_types=1);

namespace CORS\Bundle\AdminerBundle;

use Pimcore\Extension\Bundle\Installer\SettingsStoreAwareInstaller;
use Symfony\Component\HttpKernel\Bundle\BundleInterface;

class Installer extends SettingsStoreAwareInstaller
{
    public function __construct(
        protected BundleInterface $bundle
    ) {
        parent::__construct($bundle);
    }

    public function install(): void
    {
        parent::install();
    }

    public function uninstall(): void
    {
        parent::uninstall();
    }
}
