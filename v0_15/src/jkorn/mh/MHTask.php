<?php
/**
 * Created by PhpStorm.
 * User: jkorn2324
 * Date: 2020-05-06
 * Time: 23:06
 */

declare(strict_types=1);

namespace jkorn\mh;


use pocketmine\scheduler\Task;
use pocketmine\Server;

class MHTask extends Task
{
    /** @var Server */
    private $server;
    /** @var int */
    private $currentTick = 0;

    public function __construct(MHMain $main)
    {
        $this->server = $main->getServer();
        $this->server->getScheduler()->scheduleRepeatingTask($this, 1);
    }

    /**
     * Actions to execute when run
     *
     * @param $currentTick
     *
     * @return void
     */
    public function onRun($currentTick)
    {
        // Updates the current tick every second.
        if($this->currentTick % 20 == 0)
        {
            MHMain::getPlayerManager()->updateCooldown();
        }

        $this->currentTick++;
    }
}