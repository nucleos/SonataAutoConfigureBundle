<?php

declare(strict_types=1);

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nucleos\SonataAutoConfigureBundle\Tests\Fixtures\Admin;

use Nucleos\SonataAutoConfigureBundle\Attribute\Admin;
use Sonata\AdminBundle\Admin\AbstractAdmin;

/**
 * @extends AbstractAdmin<object>
 */
#[Admin(autowireEntity: false, templates: null)]
class DisableAutowireEntityAdmin extends AbstractAdmin {}
