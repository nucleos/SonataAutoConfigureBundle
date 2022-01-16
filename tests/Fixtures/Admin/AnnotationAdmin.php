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
use Nucleos\SonataAutoConfigureBundle\Tests\Fixtures\Entity\Category;

/**
 * @Sonata\AdminOptions(
 *     label="This is a Label",
 *     entity=Category::class,
 *     group="not test",
 *     translationDomain="Foo",
 *     showInDashboard=true,
 *     showMosaicButton=true,
 *     keepOpen=false,
 *     onTop=false,
 *     templates={
 *         "foo": "foo.html.twig"
 *     },
 *     children={
 *         "admin.product"
 *     }
 * )
 */
class AnnotationAdmin
{
}
