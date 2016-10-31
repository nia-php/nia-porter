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

use NiaPorter\Porter\CompositePorter;
use NiaPorter\Porter\Php7\AnonymousClassPorter;
use NiaPorter\Porter\Php7\DeclareStrictPorter;
use NiaPorter\Porter\Php7\NullCoalesceOperatorPorter;
use NiaPorter\Porter\Php7\ParameterPrimitiveTypeHintPorter;
use NiaPorter\Porter\Php7\ReturnTypeHintPorter;
require_once __DIR__ . '/../vendor/autoload.php';

/**
 * The application to port a directory.
 */
class Application implements ApplicationInterface
{

    /**
     * Constructor.
     */
    public function __construct()
    {
        error_reporting(- 1);
        define('LF', "\n");
    }

    /**
     *
     * {@inheritdoc}
     *
     * @see \NiaPorter\ApplicationInterface::run($argv)
     */
    public function run(array $argv)
    {
        printf('nia-porter by Patrick Ullmann and contributors.' . LF);
        printf('---' . LF);

        if (count($argv) !== 2) {
            printf('usage: %s <path to vendor nia directory>' . LF, pathinfo($argv[0], PATHINFO_FILENAME));
            return 1;
        }

        $directory = $argv[1];

        if (! is_dir($directory)) {
            printf('Passed path is not a directory: %s' . LF, $directory);
            return 1;
        }

        $porter = new CompositePorter([
            new DeclareStrictPorter(),
            new ReturnTypeHintPorter(),
            new ParameterPrimitiveTypeHintPorter(),
            new NullCoalesceOperatorPorter(),
            new AnonymousClassPorter()
        ]);

        $iterator = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($directory));
        $regexIterator = new \RegexIterator($iterator, '/^.+\.php$/i', \RecursiveRegexIterator::GET_MATCH);

        $counters = [
            'okay' => 0,
            'ignored' => 0,
            'error' => 0
        ];

        foreach ($regexIterator as $meta) {
            $file = $meta[0];
            printf('processing: %s ... ', $file);

            $oldContent = file_get_contents($file);
            $newContent = $porter->port($oldContent);

            if ($oldContent === $newContent) {
                printf('[ignored]' . LF);
                ++ $counters['ignored'];
                continue;
            }

            file_put_contents($file, $newContent);

            $execOutput = null;
            $execStatus = null;
            exec('php -l ' . escapeshellarg($file), $execOutput, $execStatus);

            if ($execStatus !== 0) {
                printf('[error]' . LF);
                ++ $counters['error'];
                continue;
            }

            printf('[okay]' . LF);
            ++ $counters['okay'];
        }

        printf('---' . LF);
        printf('%s (%d files: %d successfully, %d failures, %d ignored)' . LF, $counters['error'] ? 'Failed' : 'Successfully', array_sum($counters), $counters['okay'], $counters['error'], $counters['ignored']);

        return (int) ($counters['error'] !== 0);
    }
}

$application = new Application();
exit($application->run($_SERVER['argv']));
