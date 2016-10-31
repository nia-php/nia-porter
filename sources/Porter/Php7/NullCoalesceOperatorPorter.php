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
 * Ports the PHP 7 null coalesce operator feature to PHP 5.6
 */
class NullCoalesceOperatorPorter implements PorterInterface
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
        preg_match_all('/\$(?P<variable>[a-zA-Z0-9_\[\]\$\'\"]+)\s*\?\?/m', $content, $matches, PREG_SET_ORDER);

        foreach ($matches as $match) {
            $replacement = 'isset($' . $match['variable'] . ') ? $' . $match['variable'] . ' :';

            $content = preg_replace('/' . preg_quote($match[0]) . '/', $replacement, $content, 1);
        }

        return $content;
    }
}

