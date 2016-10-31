<?php
/*
 * This file is part of the nia porter.
 *
 * (c) Patrick Ullmann <patrick.ullmann@nat-software.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Test\NiaPorter\Porter\Php7;

use PHPUnit_Framework_TestCase;
use NiaPorter\Porter\Php7\AnonymousClassPorter;

/**
 * Unit test for \NiaPorter\Porter\Php7\AnonymousClassPorter.
 */
class AnonymousClassPorterTest extends PHPUnit_Framework_TestCase
{

    /**
     * @covers \NiaPorter\Porter\Php7\AnonymousClassPorter::port
     */
    public function testPort()
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
    public function getIterator(): Iterator
    {
        \$unused = new class() {
            public function foobar()
            {
                return __METHOD__;
            }
        };

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
declare(strict_types = 1);
namespace PorterTest;

use ArrayIterator;
use Iterator;
use IteratorAggregate;

class Foobar implements IteratorAggregate
{
    public function getIterator(): Iterator
    {
        \$unused = eval('
namespace __AnonymousClass {
use ArrayIterator;
use Iterator;
use IteratorAggregate;
class __00000000000000000000000000000000 {
            public function foobar()
            {
                return __METHOD__;
            }
        }

return new __00000000000000000000000000000000();
}');

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

            public function getIterator(): Iterator
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
        $porter = new AnonymousClassPorter();
        $actual = preg_replace('/__[0-9a-f]{32}/', '__00000000000000000000000000000000', $porter->port($actual));
        $this->assertSame($expected, $actual);
    }
}

