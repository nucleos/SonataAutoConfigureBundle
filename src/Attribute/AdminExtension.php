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
final class AdminExtension
{
    private ?bool $global;

    private ?int $priority;

    /**
     * @var string[]|null
     */
    private ?array $target;

    /**
     * @param string[]|null $target
     */
    public function __construct(
        ?bool $global = null,
        ?int $priority = null,
        ?array $target = null
    ) {
        $this->global   = $global;
        $this->priority = $priority;
        $this->target   = $target;
    }

    public function getGlobal(): ?bool
    {
        return $this->global;
    }

    public function getPriority(): ?int
    {
        return $this->priority;
    }

    /**
     * @return string[]|null
     */
    public function getTarget(): ?array
    {
        return $this->target;
    }

    /**
     * @return array<string, mixed>
     */
    public function getOptions(): array
    {
        return array_filter(
            [
                'global'   => $this->global,
                'priority' => $this->priority,
                'target'   => $this->target,
            ],
            static function (mixed $value): bool {
                return null !== $value;
            }
        );
    }
}
