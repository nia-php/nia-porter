<?php
/*
 * This file is part of the nia porter.
 *
 * (c) Patrick Ullmann <patrick.ullmann@nat-software.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace NiaPorter\Porter;

/**
 * Interface for porter implementations.
 * Porters are used to port a content into another content.
 */
interface PorterInterface
{

    /**
     * Ports the passed content.
     *
     * @param string $content
     *            The content to port.
     * @return string The ported content.
     */
    public function port($content);
}

