<?php

namespace App;

use parallel\Sync;

class CountDownLatch
{
    private Sync $sync;

    public function __construct(int $count)
    {
        $this->sync = new Sync($count);
    }

    public function countDown()
    {
        $this->sync->set($this->sync->get() - 1);
        $this->sync->notify(true);
    }

    public function awaitOn()
    {
        call_user_func($this->sync, function () {
            while($this->sync->get() !== 0) {
                $this->sync->wait();
            }
        });
    }
}