<?php
namespace Rocketeer\Plugins\Opcache;

use Rocketeer\Plugins\AbstractPlugin;
use Rocketeer\Services\Tasks\TasksHandler;

class Opcache extends AbstractPlugin
{
    /**
     * {@inheritdoc}
     */
    public function onQueue(TasksHandler $tasks)
    {
        $tasks->after('SwapSymlink', ClearOpcache::class);
    }
}