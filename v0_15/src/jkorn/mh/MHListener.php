<?php

declare(strict_types=1);

namespace jkorn\mh;


use pocketmine\event\Listener;
use pocketmine\event\player\PlayerChatEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\permission\Permission;
use pocketmine\Server;
use pocketmine\utils\TextFormat;

class MHListener implements Listener
{

    /** @var Server */
    private $server;

    public function __construct(MHMain $plugin)
    {
        $this->server = $plugin->getServer();
        $this->server->getPluginManager()->registerEvents($this, $plugin);
    }

    /**
     * @param PlayerQuitEvent $event
     *
     * Called when the player quits the game.
     */
    public function onQuit(PlayerQuitEvent $event)
    {
        MHMain::getPlayerManager()->removeFromCooldown($event->getPlayer());
    }

    /**
     * @param PlayerChatEvent $event
     *
     * Called when the player chats.
     */
    public function onChat(PlayerChatEvent $event) {

        $player = $event->getPlayer();
        if(MHMain::getChatManager()->isMuted() && !$player->hasPermission("permission.meetup.chat")) {
            $player->sendMessage(MHUtil::getPrefix() . TextFormat::RED . " Chat is muted for everyone.");
            $event->setCancelled();
        }
    }
}