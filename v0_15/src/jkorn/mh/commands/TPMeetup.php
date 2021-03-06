<?php

declare(strict_types=1);

namespace jkorn\mh\commands;


use jkorn\mh\MHMain;
use jkorn\mh\MHUtil;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

class TPMeetup extends Command
{

    public function __construct()
    {
        parent::__construct("tpmeetup", "Teleports everyone to the sender's position and gives everyone a kit.", "Usage: /tpmeetup <kit>", ["tpall"]);
        $this->setPermission("permission.meetup.tp");
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

            if($sender instanceof Player) {

                if(!MHMain::getPlayerManager()->putInCooldown($sender)) {
                    $sender->sendMessage(MHUtil::getPrefix() . TextFormat::RED . " You are on cooldown for this command.");
                    return true;
                }

                if(isset($args[0])) {
                    $output = MHUtil::giveEveryoneAKit($args[0]);
                    if($output < 2) {
                        if($output == 0) {
                            $sender->sendMessage(MHUtil::getPrefix() . TextFormat::RED . " No kit plugin exists! (AdvancedKits or KitKB)");
                        } else {
                            $sender->sendMessage(MHUtil::getPrefix() . TextFormat::RED . " That kit '{$args[0]}' doesn't exist.");
                        }
                        return true;
                    }
                }

                $seconds = MHMain::getPlayerManager()->getCooldown();
                MHUtil::tpEveryoneTo($sender);
                $sender->sendMessage(MHUtil::getPrefix() . TextFormat::GREEN . " Successfully teleported everyone to you. You are now on cooldown for this command for {$seconds} seconds.");

            } else {
                $sender->sendMessage(MHUtil::getPrefix() . TextFormat::RED . "Console can't run this command.");
            }
        }
        return true;
    }
}