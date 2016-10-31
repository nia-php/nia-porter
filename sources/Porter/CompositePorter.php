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
 * Composite porter implementation.
 */
class CompositePorter implements CompositePorterInterface
{

    /**
     * List with assigned porters.
     *
     * @var PorterInterface[]
     */
    private $porters = [];

    /**
     * Constructor.
     *
     * @param PorterInterface[] $porters
     *            List with porters to assign.
     */
    public function __construct(array $porters = [])
    {
        foreach ($porters as $porter) {
            $this->addPorter($porter);
        }
    }

    /**
     *
     * {@inheritdoc}
     *
     * @see \NiaPorter\Porter\PorterInterface::port($content)
     */
    public function port($content)
    {
        foreach ($this->getPorters() as $porter) {
            $content = $porter->port($content);
        }

        return $content;
    }

    /**
     *
     * {@inheritdoc}
     *
     * @see \NiaPorter\Porter\CompositePorterInterface::addPorter($porter)
     */
    public function addPorter(PorterInterface $porter)
    {
        $this->porters[] = $porter;

        return $this;
    }

    /**
     *
     * {@inheritdoc}
     *
     * @see \NiaPorter\Porter\CompositePorterInterface::getPorters()
     */
    public function getPorters()
    {
        return $this->porters;
    }
}

