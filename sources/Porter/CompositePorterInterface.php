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
 * Interface for composite porter implementations.
 * Composite porters are used to combine multiple porters and use them as one.
 */
interface CompositePorterInterface extends PorterInterface
{

    /**
     * Adds a porter.
     *
     * @param PorterInterface $porter
     *            The porter to add.
     * @return CompositePorterInterface Reference to this instance.
     */
    public function addPorter(PorterInterface $porter);

    /**
     * Returns a list of all assigned porters.
     *
     * @return PorterInterface[] List of all assigned porters.
     */
    public function getPorters();
}

