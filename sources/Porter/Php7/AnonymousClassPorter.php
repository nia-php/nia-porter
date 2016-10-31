<?php
/*
 * This file is part of the nia porter.
 *
 * (c) Patrick Ullmann <patrick.ullmann@nat-software.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace NiaPorter\Porter\Php7;

use NiaPorter\Porter\PorterInterface;

/**
 * Ports the PHP 7 anonymous class feature to PHP 5.6
 */
class AnonymousClassPorter implements PorterInterface
{

    /**
     *
     * {@inheritdoc}
     *
     * @see \NiaPorter\Porter\PorterInterface::port($content)
     */
    public function port($content)
    {
        $matches = [];

        // extract use statements.
        preg_match_all('/^use\s+[^;]+;/m', $content, $matches, PREG_SET_ORDER);

        $usings = '';
        foreach ($matches as $match) {
            $usings .= $match[0] . "\n";
        }

        // extract classes
        preg_match_all('/new\s+class\s*\((?P<arguments>[^)]*)\)(?P<body>.*?)\}\;/s', $content, $matches, PREG_SET_ORDER);

        foreach ($matches as $match) {
            if ($match[0] === '') {
                continue;
            }

            $namespace = '__AnonymousClass';
            $className = '__' . md5(uniqid());
            $replacement = '';
            $replacement .= 'eval(\'' . "\n";
            $replacement .= 'namespace ' . $namespace . ' {' . "\n";
            $replacement .= $usings . 'class ' . $className . addcslashes($match['body'], "'") . '}' . "\n";
            $replacement .= '' . "\n";
            $replacement .= 'return new ' . $className . '(' . addcslashes($match['arguments'], "'") . ');' . "\n";
            $replacement .= '}\');';

            $content = preg_replace('/' . preg_quote($match[0]) . '/', $replacement, $content, 1);
        }

        return $content;
    }
}

