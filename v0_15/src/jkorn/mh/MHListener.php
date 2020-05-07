<?php

declare(strict_types=1);

namespace jkorn\mh;


use pocketmine\event\Listener;
use pocketmine\event\player\PlayerChatEvent;
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
     * @param PlayerChatEvent $event
     *
     * Called when the player chats.
     */
    public function onChat(PlayerChatEvent $event) {

        $player = $event->getPlayer();
        if(MHMain::getChatManager()->isMuted()) {
            $player->sendMessage(MHUtil::getPrefix() . TextFormat::RED . " Chat is muted for everyone.");
            $event->setCancelled();
        }
    }
}