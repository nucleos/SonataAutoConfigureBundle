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

namespace Nucleos\SonataAutoConfigureBundle;

use Nucleos\SonataAutoConfigureBundle\DependencyInjection\Compiler\AutoConfigureAdminClassesCompilerPass;
use Nucleos\SonataAutoConfigureBundle\DependencyInjection\Compiler\AutoConfigureAdminExtensionsCompilerPass;
use Symfony\Component\DependencyInjection\Compiler\PassConfig;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

final class SonataAutoConfigureBundle extends Bundle
{
    public function build(ContainerBuilder $container): void
    {
        $container
            ->addCompilerPass(new AutoConfigureAdminClassesCompilerPass(), PassConfig::TYPE_BEFORE_OPTIMIZATION, 10)
            ->addCompilerPass(new AutoConfigureAdminExtensionsCompilerPass(), PassConfig::TYPE_BEFORE_OPTIMIZATION, 10)
        ;
    }
}
