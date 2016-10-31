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
use NiaPorter\Porter\Php7\NullCoalesceOperatorPorter;

/**
 * Unit test for \NiaPorter\Porter\Php7\NullCoalesceOperatorPorter.
 */
class NullCoalesceOperatorPorterTest extends PHPUnit_Framework_TestCase
{

    /**
     * @covers \NiaPorter\Porter\Php7\NullCoalesceOperatorPorter::port
     */
    public function testPort()
    {
        $actual = <<<EOL
<?php
declare(strict_types = 1);
namespace PorterTest;

class Foobar
{
    /**
     * @param string \$string
     * @return string
     */
    public function nullCoalesceOperator(string \$string): string
    {
        \$string = \$string ?? \$string;
        return \$string ?? 'Hello';
    }
}
EOL;

        $expected = <<<EOL
<?php
declare(strict_types = 1);
namespace PorterTest;

class Foobar
{
    /**
     * @param string \$string
     * @return string
     */
    public function nullCoalesceOperator(string \$string): string
    {
        \$string = isset(\$string) ? \$string : \$string;
        return isset(\$string) ? \$string : 'Hello';
    }
}
EOL;

        $porter = new NullCoalesceOperatorPorter();
        $this->assertSame($expected, $porter->port($actual));
    }
}

