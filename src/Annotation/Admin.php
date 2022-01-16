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

namespace Nucleos\SonataAutoConfigureBundle\Annotation;

/**
 * @Annotation
 * @Target("CLASS")
 */
final class Admin
{
    public ?string $label = null;

    public ?string $managerType = null;

    public ?string $group = null;

    public ?bool $showInDashboard = null;

    public ?bool $showMosaicButton = null;

    public ?bool $keepOpen = null;

    public ?bool $onTop = null;

    public ?string $icon = null;

    public ?string $labelTranslatorStrategy = null;

    public ?string $labelCatalogue = null;

    public ?string $translationDomain = null;

    public ?string $pagerType = null;

    public ?string $adminCode = null;

    public ?string $entity = null;

    public ?string $controller = null;

    public ?bool $autowireEntity = true;

    /**
     * @var array<string, string>|null
     */
    public ?array $templates = [];

    /**
     * @var string[]|null
     */
    public ?array $children = [];

    /**
     * @return array<string, mixed>
     */
    public function getOptions(): array
    {
        return array_filter(
            [
                'manager_type'              => $this->managerType,
                'group'                     => $this->group,
                'label'                     => $this->label,
                'show_in_dashboard'         => $this->showInDashboard,
                'show_mosaic_button'        => $this->showMosaicButton,
                'keep_open'                 => $this->keepOpen,
                'on_top'                    => $this->onTop,
                'icon'                      => $this->icon,
                'label_translator_strategy' => $this->labelTranslatorStrategy,
                'label_catalogue'           => $this->labelCatalogue,
                'pager_type'                => $this->pagerType,
            ],
            static function (mixed $value): bool {
                return null !== $value;
            }
        );
    }
}
