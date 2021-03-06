<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\Validator\Tests\Constraints;

use Symfony\Component\Validator\Constraints\LessThan;
use Symfony\Component\Validator\Constraints\LessThanValidator;

/**
 * @author Daniel Holmes <daniel@danielholmes.org>
 */
class LessThanValidatorTest extends AbstractComparisonValidatorTestCase
{
    protected function createValidator()
    {
        return new LessThanValidator();
    }

    protected function createConstraint(array $options)
    {
        return new LessThan($options);
    }

    /**
     * {@inheritdoc}
     */
    public function provideValidComparisons()
    {
        return array(
            array(1, 2),
            array(new \DateTime('2000-01-01'), new \DateTime('2010-01-01')),
            array('22', '333'),
            array(null, 1),
        );
    }

    /**
     * {@inheritdoc}
     */
    public function provideInvalidComparisons()
    {
        return array(
            array(3, 2, '2', 'integer'),
            array(2, 2, '2', 'integer'),
            array(new \DateTime('2010-01-01'), new \DateTime('2000-01-01'), '2000-01-01 00:00:00', 'DateTime'),
            array(new \DateTime('2000-01-01'), new \DateTime('2000-01-01'), '2000-01-01 00:00:00', 'DateTime'),
            array('333', '22', "'22'", 'string'),
        );
    }
}
