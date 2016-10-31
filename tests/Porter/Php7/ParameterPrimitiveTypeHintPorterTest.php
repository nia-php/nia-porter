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
use NiaPorter\Porter\Php7\ParameterPrimitiveTypeHintPorter;

/**
 * Unit test for \NiaPorter\Porter\Php7\ParameterPrimitiveTypeHintPorter.
 */
class ParameterPrimitiveTypeHintPorterTest extends PHPUnit_Framework_TestCase
{

    /**
     * @covers \NiaPorter\Porter\Php7\ParameterPrimitiveTypeHintPorter::port
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
     * @param int \$int
     * @param bool \$bool
     * @param array \$array
     * @return string
     */
    public function parameterPrimitiveTypeHint(string \$string, int \$int, bool \$bool, array \$array): string
    {
        return \$string;
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
     * @param int \$int
     * @param bool \$bool
     * @param array \$array
     * @return string
     */
    public function parameterPrimitiveTypeHint(\$string, \$int, \$bool, array \$array): string
    {
        return \$string;
    }
}
EOL;

        $porter = new ParameterPrimitiveTypeHintPorter();
        $this->assertSame($expected, $porter->port($actual));
    }
}

