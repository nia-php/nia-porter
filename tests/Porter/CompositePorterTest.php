<?php
/*
 * This file is part of the nia porter.
 *
 * (c) Patrick Ullmann <patrick.ullmann@nat-software.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Test\NiaPorter\Porter;

use PHPUnit_Framework_TestCase;
use NiaPorter\Porter\CompositePorter;
use NiaPorter\Porter\Php7\DeclareStrictPorter;
use NiaPorter\Porter\Php7\ReturnTypeHintPorter;
use NiaPorter\Porter\Php7\ParameterPrimitiveTypeHintPorter;
use NiaPorter\Porter\Php7\NullCoalesceOperatorPorter;
use NiaPorter\Porter\Php7\AnonymousClassPorter;

/**
 * Unit test for \NiaPorter\Porter\CompositePorter.
 */
class CompositePorterTest extends PHPUnit_Framework_TestCase
{

    /**
     * @covers \NiaPorter\Porter\CompositePorter
     */
    public function testComposite()
    {
        $actual = <<<EOL
<?php
declare(strict_types = 1);
namespace PorterTest;

use ArrayIterator;
use Iterator;
use IteratorAggregate;

class Foobar implements IteratorAggregate
{

    /**
     * @param string \$string
     * @param int \$int
     * @param bool \$bool
     * @param array \$array
     * @return string
     */
    public function parameterPrimitiveTypeHint(string \$string, int \$int, bool \$bool, array \$array): string
    {
        return \$string;
    }

    /**
     * @param string \$string
     * @return string
     */
    public function returnTypeHint(string \$string): string
    {
        return \$string;
    }

    /**
     * @param string \$string
     * @return string
     */
    public function nullCoalesceOperator(string \$string): string
    {
        \$string = \$string ?? \$string;
        return \$string ?? 'Hello';
    }

    public function getIterator(): Iterator
    {
        return new class([
            "hallo",
            123,
            'abc'
        ]) implements IteratorAggregate {
            private \$data = null;

            public function __construct(array \$data)
            {
                \$this->data = \$data;
            }

            public function getIterator(): Iterator
            {
                return new ArrayIterator(\$this->data);
            }
        };
    }
}
EOL;

        $expected = <<<EOL
<?php

namespace PorterTest;

use ArrayIterator;
use Iterator;
use IteratorAggregate;

class Foobar implements IteratorAggregate
{

    /**
     * @param string \$string
     * @param int \$int
     * @param bool \$bool
     * @param array \$array
     * @return string
     */
    public function parameterPrimitiveTypeHint(\$string, \$int, \$bool, array \$array)
    {
        return \$string;
    }

    /**
     * @param string \$string
     * @return string
     */
    public function returnTypeHint(\$string)
    {
        return \$string;
    }

    /**
     * @param string \$string
     * @return string
     */
    public function nullCoalesceOperator(\$string)
    {
        \$string = isset(\$string) ? \$string : \$string;
        return isset(\$string) ? \$string : 'Hello';
    }

    public function getIterator()
    {
        return eval('
namespace __AnonymousClass {
use ArrayIterator;
use Iterator;
use IteratorAggregate;
class __00000000000000000000000000000000 implements IteratorAggregate {
            private \$data = null;

            public function __construct(array \$data)
            {
                \$this->data = \$data;
            }

            public function getIterator()
            {
                return new ArrayIterator(\$this->data);
            }
        }

return new __00000000000000000000000000000000([
            "hallo",
            123,
            \'abc\'
        ]);
}');
    }
}
EOL;

        $porter = new CompositePorter([
            new DeclareStrictPorter(),
            new ReturnTypeHintPorter(),
            new ParameterPrimitiveTypeHintPorter(),
            new NullCoalesceOperatorPorter(),
            new AnonymousClassPorter()
        ]);

        $actual = preg_replace('/__[0-9a-f]{32}/', '__00000000000000000000000000000000', $porter->port($actual));
        $this->assertSame($expected, $actual);
    }
}

