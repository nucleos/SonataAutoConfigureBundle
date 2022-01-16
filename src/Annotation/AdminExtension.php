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
final class AdminExtension
{
    public ?bool $global = null;

    public ?int $priority = null;

    /**
     * @var string[]|null
     */
    public ?array $target = null;

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
