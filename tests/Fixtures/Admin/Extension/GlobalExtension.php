<?php

declare(strict_types=1);

/*
 * This file is part of the SonataAutoConfigureBundle package.
 *
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nucleos\SonataAutoConfigureBundle\Tests\Fixtures\Admin\Extension;

use Nucleos\SonataAutoConfigureBundle\Attribute\AdminExtension;
use Sonata\AdminBundle\Admin\AbstractAdminExtension;

/**
 * @extends AbstractAdminExtension<object>
 */
#[AdminExtension(global: true)]
class GlobalExtension extends AbstractAdminExtension {}
