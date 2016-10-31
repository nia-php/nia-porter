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
 * Ports the PHP 7 parameter primitive type hint feature to PHP 5.6
 */
class ParameterPrimitiveTypeHintPorter implements PorterInterface
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
        preg_match_all('/function\s*\w*\s*\((.*)\)/m', $content, $matches, PREG_SET_ORDER);

        foreach ($matches as $match) {
            if ($match[1] === '') {
                continue;
            }

            $parameters = array_map('trim', explode(',', $match[1]));

            $parameters = array_map(function ($parameter) {
                return preg_replace('/^(string|int|bool|float)\s+/m', '', $parameter);
            }, $parameters);

            $content = preg_replace('/\(\s*' . preg_quote($match[1]) . '\s*\)/', '(' . implode(', ', $parameters) . ')', $content, 1);
        }

        return $content;
    }
}

