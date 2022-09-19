<?php

declare(strict_types=1);

namespace App;

class SynchronizedThreads
{
    public function __invoke()
    {
        \parallel\bootstrap('vendor/autoload.php');

        $threadCount = 4;
        $startLatch = new CountDownLatch(1);
        $endLatch = new CountDownLatch($threadCount);

        for ($i = 1; $i <= $threadCount; $i++) {
            \parallel\run(function ($startLatch, $endLatch, $threadId) {
                echo 'Thread ' . $threadId . ' started and wait' . PHP_EOL;
                $startLatch->awaitOn();

                for ($i=0; $i < 100; $i++) {
                    echo $threadId;
                    usleep(10000);
                }

                $endLatch->countDown();
            }, [$startLatch, $endLatch, $i]);
        }

        sleep(1);
        echo 'Synchronized Starting threads' . PHP_EOL;
        $startLatch->countDown();

        echo 'Main thread waits for all threads to finish' . PHP_EOL;
        $endLatch->awaitOn();

        echo PHP_EOL;
        echo 'Exit' . PHP_EOL;
    }
}