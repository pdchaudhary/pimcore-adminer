<?php

declare(strict_types=1);

/*
 * CORS GmbH
 *
 * This source file is available under the GNU General Public License version 3 (GPLv3) license
 *
 * Full copyright and license information is available in
 * LICENSE.md which is distributed with this source code.
 *
 * @copyright  Copyright (c) CORS GmbH (https://www.cors.gmbh)
 * @license    https://www.cors.gmbh/license     GPLv3 and CCL
 *
 */

namespace CORS\Bundle\AdminerBundle;

use Pimcore\Extension\Bundle\AbstractPimcoreBundle;

class CORSAdminerBundle extends AbstractPimcoreBundle
{
    public function getNiceName(): string
    {
        return 'CORS - Adminer Bundle';
    }

    public function getInstaller(): ?Installer
    {
        return $this->container?->get(Installer::class);
    }
}
