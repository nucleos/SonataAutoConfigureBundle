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

namespace Nucleos\SonataAutoConfigureBundle\Attribute;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
final class Admin
{
    private ?string $label;

    private ?string $managerType;

    private ?string $group;

    private ?bool $showInDashboard;

    private ?bool $showMosaicButton;

    private ?bool $keepOpen;

    private ?bool $onTop;

    private ?string $icon;

    private ?string $labelTranslatorStrategy;

    private ?string $labelCatalogue;

    private ?string $translationDomain;

    private ?string $pagerType;

    private ?string $adminCode;

    private ?string $entity;

    private ?string $controller;

    private ?bool $autowireEntity;

    /**
     * @var array<string, string>|null
     */
    private ?array $templates;

    /**
     * @var string[]|null
     */
    private ?array $children;

    /**
     * @param array<string, string>|null $templates
     * @param string[]|null              $children
     *
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     */
    public function __construct(
        ?string $label = null,
        ?string $managerType = null,
        ?string $group = null,
        ?bool $showInDashboard = null,
        ?bool $showMosaicButton = null,
        ?bool $keepOpen = null,
        ?bool $onTop = null,
        ?string $icon = null,
        ?string $labelTranslatorStrategy = null,
        ?string $labelCatalogue = null,
        ?string $translationDomain = null,
        ?string $pagerType = null,
        ?string $adminCode = null,
        ?string $entity = null,
        ?string $controller = null,
        ?bool $autowireEntity = null,
        ?array $templates = null,
        ?array $children = null
    ) {
        $this->label                   = $label;
        $this->managerType             = $managerType;
        $this->group                   = $group;
        $this->showInDashboard         = $showInDashboard;
        $this->showMosaicButton        = $showMosaicButton;
        $this->keepOpen                = $keepOpen;
        $this->onTop                   = $onTop;
        $this->icon                    = $icon;
        $this->labelTranslatorStrategy = $labelTranslatorStrategy;
        $this->labelCatalogue          = $labelCatalogue;
        $this->translationDomain       = $translationDomain;
        $this->pagerType               = $pagerType;
        $this->adminCode               = $adminCode;
        $this->entity                  = $entity;
        $this->controller              = $controller;
        $this->autowireEntity          = $autowireEntity ?? true;
        $this->templates               = $templates      ?? [];
        $this->children                = $children       ?? [];
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function setLabel(?string $label): void
    {
        $this->label = $label;
    }

    public function getManagerType(): ?string
    {
        return $this->managerType;
    }

    public function setManagerType(?string $managerType): void
    {
        $this->managerType = $managerType;
    }

    public function getGroup(): ?string
    {
        return $this->group;
    }

    public function setGroup(?string $group): void
    {
        $this->group = $group;
    }

    public function getShowInDashboard(): ?bool
    {
        return $this->showInDashboard;
    }

    public function setShowInDashboard(?bool $showInDashboard): void
    {
        $this->showInDashboard = $showInDashboard;
    }

    public function getShowMosaicButton(): ?bool
    {
        return $this->showMosaicButton;
    }

    public function setShowMosaicButton(?bool $showMosaicButton): void
    {
        $this->showMosaicButton = $showMosaicButton;
    }

    public function getKeepOpen(): ?bool
    {
        return $this->keepOpen;
    }

    public function setKeepOpen(?bool $keepOpen): void
    {
        $this->keepOpen = $keepOpen;
    }

    public function getOnTop(): ?bool
    {
        return $this->onTop;
    }

    public function setOnTop(?bool $onTop): void
    {
        $this->onTop = $onTop;
    }

    public function getIcon(): ?string
    {
        return $this->icon;
    }

    public function setIcon(?string $icon): void
    {
        $this->icon = $icon;
    }

    public function getLabelTranslatorStrategy(): ?string
    {
        return $this->labelTranslatorStrategy;
    }

    public function setLabelTranslatorStrategy(?string $labelTranslatorStrategy): void
    {
        $this->labelTranslatorStrategy = $labelTranslatorStrategy;
    }

    public function getLabelCatalogue(): ?string
    {
        return $this->labelCatalogue;
    }

    public function setLabelCatalogue(?string $labelCatalogue): void
    {
        $this->labelCatalogue = $labelCatalogue;
    }

    public function getTranslationDomain(): ?string
    {
        return $this->translationDomain;
    }

    public function setTranslationDomain(?string $translationDomain): void
    {
        $this->translationDomain = $translationDomain;
    }

    public function getPagerType(): ?string
    {
        return $this->pagerType;
    }

    public function setPagerType(?string $pagerType): void
    {
        $this->pagerType = $pagerType;
    }

    public function getAdminCode(): ?string
    {
        return $this->adminCode;
    }

    public function setAdminCode(?string $adminCode): void
    {
        $this->adminCode = $adminCode;
    }

    public function getEntity(): ?string
    {
        return $this->entity;
    }

    public function setEntity(?string $entity): void
    {
        $this->entity = $entity;
    }

    public function getController(): ?string
    {
        return $this->controller;
    }

    public function setController(?string $controller): void
    {
        $this->controller = $controller;
    }

    public function getAutowireEntity(): ?bool
    {
        return $this->autowireEntity;
    }

    public function setAutowireEntity(?bool $autowireEntity): void
    {
        $this->autowireEntity = $autowireEntity;
    }

    /**
     * @return string[]|null
     */
    public function getTemplates(): ?array
    {
        return $this->templates;
    }

    /**
     * @param string[]|null $templates
     */
    public function setTemplates(?array $templates): void
    {
        $this->templates = $templates;
    }

    /**
     * @return string[]|null
     */
    public function getChildren(): ?array
    {
        return $this->children;
    }

    /**
     * @param string[]|null $children
     */
    public function setChildren(?array $children): void
    {
        $this->children = $children;
    }

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
