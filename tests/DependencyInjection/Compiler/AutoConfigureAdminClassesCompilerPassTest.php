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

namespace Nucleos\SonataAutoConfigureBundle\Tests\DependencyInjection\Compiler;

use Nucleos\SonataAutoConfigureBundle\DependencyInjection\Compiler\AutoConfigureAdminClassesCompilerPass;
use Nucleos\SonataAutoConfigureBundle\DependencyInjection\SonataAutoConfigureExtension;
use Nucleos\SonataAutoConfigureBundle\Exception\EntityNotFound;
use Nucleos\SonataAutoConfigureBundle\Tests\Fixtures\Admin\AttributedAdmin;
use Nucleos\SonataAutoConfigureBundle\Tests\Fixtures\Admin\CategoryAdmin;
use Nucleos\SonataAutoConfigureBundle\Tests\Fixtures\Admin\DisableAutowireEntityAdmin;
use Nucleos\SonataAutoConfigureBundle\Tests\Fixtures\Admin\NoEntityAdmin;
use Nucleos\SonataAutoConfigureBundle\Tests\Fixtures\Entity\Category;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException;

final class AutoConfigureAdminClassesCompilerPassTest extends TestCase
{
    private AutoConfigureAdminClassesCompilerPass $autoConfigureAdminClassesCompilerPass;

    private ContainerBuilder $containerBuilder;

    protected function setUp(): void
    {
        $this->autoConfigureAdminClassesCompilerPass = new AutoConfigureAdminClassesCompilerPass();
        $this->containerBuilder                      = new ContainerBuilder();

        $this->containerBuilder->registerExtension(new SonataAutoConfigureExtension());
    }

    /**
     * @dataProvider provideProcessCases
     *
     * @param array<string, mixed> $tagOptions
     * @param string[]             $methodCalls
     */
    public function testProcess(
        string $admin,
        ?string $entity,
        ?string $adminCode,
        array $tagOptions,
        array $methodCalls = []
    ): void {
        $this->loadConfig([
            'admin' => [
                'group' => 'test',
            ],
            'entity' => [
                'namespaces' => [
                    [
                        'namespace' => 'Nucleos\SonataAutoConfigureBundle\Tests\Fixtures\Entity',
                    ],
                ],
            ],
            'controller' => [
                'namespaces' => [
                    'Nucleos\SonataAutoConfigureBundle\Tests\Fixtures\Controller',
                ],
            ],
        ]);

        $definitionId = $adminCode ?? $admin;

        $this->containerBuilder->setDefinition(
            $definitionId,
            (new Definition($admin))->addTag('sonata.admin')->setAutoconfigured(true)
        );

        $this->autoConfigureAdminClassesCompilerPass->process($this->containerBuilder);

        $adminDefinition = $this->containerBuilder->getDefinition($definitionId);

        self::assertSame(
            $tagOptions,
            $adminDefinition->getTag('sonata.admin')[0]
        );

        self::assertSame(
            $entity,
            $adminDefinition->getArgument(1)
        );

        foreach ($methodCalls as $methodCall) {
            self::assertTrue($adminDefinition->hasMethodCall($methodCall));
        }
    }

    /**
     * @return mixed[]
     */
    public function provideProcessCases(): iterable
    {
        yield [
            CategoryAdmin::class,
            Category::class,
            'admin.category',
            [
                'manager_type' => 'orm',
                'group'        => 'test',
                'label'        => 'Category',
            ],
        ];

        yield [
            AttributedAdmin::class,
            Category::class,
            null,
            [
                'manager_type'       => 'orm',
                'group'              => 'not test',
                'label'              => 'This is a Label',
                'show_in_dashboard'  => true,
                'show_mosaic_button' => true,
                'keep_open'          => false,
                'on_top'             => false,
            ],
            [
                'setTemplate',
                'setTranslationDomain',
                'addChild',
            ],
        ];

        yield [
            DisableAutowireEntityAdmin::class,
            null,
            'admin.disable_autowire_entity',
            [
                'manager_type' => 'orm',
                'group'        => 'test',
                'label'        => 'Disable Autowire Entity',
            ],
        ];
    }

    public function testProcessSkipAutoConfigured(): void
    {
        $this->loadConfig();
        $this->containerBuilder->setDefinition(
            CategoryAdmin::class,
            (new Definition(CategoryAdmin::class))->addTag('sonata.admin')->setAutoconfigured(false)
        );

        $this->autoConfigureAdminClassesCompilerPass->process($this->containerBuilder);

        $this->expectException(ServiceNotFoundException::class);
        $this->containerBuilder->getDefinition('admin.category');
    }

    public function testProcessEntityNotFound(): void
    {
        $this->loadConfig();
        $this->containerBuilder->setDefinition(
            NoEntityAdmin::class,
            (new Definition(NoEntityAdmin::class))->addTag('sonata.admin')->setAutoconfigured(true)
        );

        $this->expectException(EntityNotFound::class);
        $this->autoConfigureAdminClassesCompilerPass->process($this->containerBuilder);
    }

    /**
     * @param mixed[] $config
     */
    private function loadConfig(array $config = []): void
    {
        (new SonataAutoConfigureExtension())->load([
            'sonata_auto_configure' => $config,
        ], $this->containerBuilder);
    }
}
