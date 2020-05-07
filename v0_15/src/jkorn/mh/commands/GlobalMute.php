<?php

declare(strict_types=1);

namespace jkorn\mh\commands;


use jkorn\mh\MHMain;
use jkorn\mh\MHUtil;
use jkorn\mh\utils\MHChatManager;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Server;
use pocketmine\utils\TextFormat;

class GlobalMute extends Command
{

    public function __construct()
    {
        parent::__construct("globalmute", "Mutes the chat for everyone besides ops.", "Usage: /globalmute {enabled|disabled}", ["gmute"]);
        $this->setPermission("permission.meetup.gm");
    }

    /**
     * @param CommandSender $sender
     * @param string $commandLabel
     * @param string[] $args
     *
     * @return mixed
     */
    public function execute(CommandSender $sender, $commandLabel, array $args)
    {
        if($this->testPermission($sender)) {

            if(!isset($args[0]) || ($enabled = MHUtil::stringToBool($args[0])) == null) {
                $sender->sendMessage(MHUtil::getPrefix() . " " . TextFormat::RED . $this->getUsage());
                return true;
            }

            $newValue = $enabled ? "muted" : "un-muted";
            if(MHMain::getChatManager()->setMuted($enabled)) {
                $newColor = $enabled ? TextFormat::RED : TextFormat::GREEN;
                $players = Server::getInstance()->getOnlinePlayers();
                foreach($players as $player) {
                    $player->sendMessage(MHUtil::getPrefix() . " " . $newColor . "Chat is now {$newValue}!");
                }
            } else {
                $sender->sendMessage(MHUtil::getPrefix() . " " . TextFormat::RED . "Chat is already {$newValue}.");
            }
        }

        return true;
    }
}