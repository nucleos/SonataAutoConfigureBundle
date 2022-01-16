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

namespace Nucleos\SonataAutoConfigureBundle\Tests\DependencyInjection;

use Matthias\SymfonyDependencyInjectionTest\PhpUnit\AbstractExtensionTestCase;
use Nucleos\SonataAutoConfigureBundle\DependencyInjection\SonataAutoConfigureExtension;

final class SonataAutoConfigureExtensionTest extends AbstractExtensionTestCase
{
    public function testParametersInContainer(): void
    {
        $this->load();

        $this->assertContainerBuilderHasParameter(
            'sonata.auto_configure.admin.suffix',
            'Admin'
        );

        $this->assertContainerBuilderHasParameter(
            'sonata.auto_configure.admin.label_translator_strategy',
            null
        );

        $this->assertContainerBuilderHasParameter(
            'sonata.auto_configure.admin.translation_domain',
            null
        );
    }

    protected function getContainerExtensions(): array
    {
        return [new SonataAutoConfigureExtension()];
    }
}
