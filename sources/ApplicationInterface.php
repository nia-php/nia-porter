<?php
/*
 * This file is part of the nia porter.
 *
 * (c) Patrick Ullmann <patrick.ullmann@nat-software.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace NiaPorter;

/**
 * Application interface.
 */
interface ApplicationInterface
{

    /**
     * Runs the application.
     *
     * @param string[] $argv
     *            List of passed arguments to the script.
     * @return int The exit code of the script.
     */
    public function run(array $argv);
}
