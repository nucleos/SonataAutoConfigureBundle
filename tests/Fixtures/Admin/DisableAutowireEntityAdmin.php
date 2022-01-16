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

namespace Nucleos\SonataAutoConfigureBundle\Tests\Fixtures\Admin;

use Nucleos\SonataAutoConfigureBundle\Annotation as Sonata;

/**
 * @Sonata\Admin(autowireEntity=false, templates=null)
 */
class DisableAutowireEntityAdmin
{
}
