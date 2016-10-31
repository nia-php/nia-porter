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
 * Ports the PHP 7 return type hint feature to PHP 5.6
 */
class ReturnTypeHintPorter implements PorterInterface
{

    /**
     *
     * {@inheritdoc}
     *
     * @see \NiaPorter\Porter\PorterInterface::port($content)
     */
    public function port($content)
    {
        $content = preg_replace('/\)(\s*\:[^\){"\'\r\n;(){-]+)$/m', ')', $content);
        $content = preg_replace('/\)(\s*\:[^\){"\';(){-]+)/m', ')', $content);

        return $content;
    }
}

