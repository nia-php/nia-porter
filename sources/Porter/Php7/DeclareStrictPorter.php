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
 * Ports the PHP 7 declare strict instruction feature to PHP 5.6
 */
class DeclareStrictPorter implements PorterInterface
{

    /**
     *
     * {@inheritdoc}
     *
     * @see \NiaPorter\Porter\PorterInterface::port($content)
     */
    public function port($content)
    {
        return preg_replace('/^declare\s*\(\s*strict_types\s*=\s*1\s*\)\s*;/m', '', $content);
    }
}

