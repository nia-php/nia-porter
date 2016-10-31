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
use NiaPorter\Porter\Php7\ReturnTypeHintPorter;

/**
 * Unit test for \NiaPorter\Porter\Php7\ReturnTypeHintPorter.
 */
class ReturnTypeHintPorterTest extends PHPUnit_Framework_TestCase
{

    /**
     * @covers \NiaPorter\Porter\Php7\ReturnTypeHintPorter::port
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
     *
     * @param string \$string
     * @return string
     */
    public function returnTypeHint(string \$string): string
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
     *
     * @param string \$string
     * @return string
     */
    public function returnTypeHint(string \$string)
    {
        return \$string;
    }
}
EOL;

        $porter = new ReturnTypeHintPorter();
        $this->assertSame($expected, $porter->port($actual));
    }
}

