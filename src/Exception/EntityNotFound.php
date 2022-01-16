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

namespace Nucleos\SonataAutoConfigureBundle\Exception;

use RuntimeException;

final class EntityNotFound extends RuntimeException implements SonataAutoConfigureExceptionInterface
{
    /**
     * @param mixed[] $namespaces
     */
    public function __construct(string $name, array $namespaces)
    {
        parent::__construct(sprintf(
            'Entity "%s" not found, looked in "%s" namespaces.',
            $name,
            implode(', ', array_column($namespaces, 'namespace'))
        ));
    }
}
