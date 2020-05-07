<?php

declare(strict_types=1);

namespace jkorn\mh;

use kitkb\Kitkb;
use AdvancedKits\Main as AdvancedKits;
use AdvancedKits\Kit as AKKit;
use pocketmine\command\Command;
use pocketmine\Player;
use pocketmine\plugin\Plugin;
use pocketmine\Server;
use pocketmine\utils\TextFormat;

class MHUtil
{
    /**
     * @param Player $player
     *
     * Teleports everyone to a certain position.
     */
    public static function tpEveryoneTo(Player $player)
    {
        $players = Server::getInstance()->getOnlinePlayers();
        foreach($players as $p) {
            if($p->getClientId() == $player->getClientId()) {
                continue;
            }
            $p->teleport($player);
        }
    }

    /**
     * @param string $kitName
     * @return int
     *
     * Gives everyone a kit according to the loaded kit plugin.
     */
    public static function giveEveryoneAKit(string $kitName)
    {
        $kitPlugin = self::getKitPlugin();
        if($kitPlugin === null)
        {
            return 0;
        }

        if($kitPlugin instanceof KitKb && $kitPlugin->isEnabled())
        {
            $kitManager = KitKb::getKitHandler();
            if($kitManager->isKit($kitName)) {
                $kit = $kitManager->getKit($kitName);

                $players = Server::getInstance()->getOnlinePlayers();
                foreach($players as $player)
                {
                    if(!$player->isSurvival() && !$player->isAdventure()) {
                        continue;
                    }
                    $player->removeAllEffects();
                    $player->sendMessage(self::getPrefix() . TextFormat::GREEN . " You have successfully received a kit for a meetup!");
                    $player->getInventory()->clearAll();
                    $kit->giveTo($player);
                }
                return 2;
            }
        } elseif ($kitPlugin instanceof AdvancedKits && $kitPlugin->isEnabled())
        {
            $kit = AdvancedKits::getKit($kitName);
            if($kit !== null && $kit instanceof AKKit)
            {
                $players = Server::getInstance()->getOnlinePlayers();
                foreach($players as $player)
                {
                    if(!$player->isSurvival() && !$player->isAdventure()) {
                        continue;
                    }
                    $player->removeAllEffects();
                    $player->sendMessage(self::getPrefix() . TextFormat::GREEN . " You have successfully received a kit for a meetup!");
                    $player->getInventory()->clearAll();
                    $kit->addTo($player);
                }
                return 2;
            }
        }

        return 1;
    }

    /**
     * @return Plugin|null
     *
     * Gets the main kit plugin that the plugin will be using.
     */
    public static function getKitPlugin()
    {
        $plugins = [
            "KitKB" => true,
            "AdvancedKits" => true
        ];

        $pluginManager = Server::getInstance()->getPluginManager();
        foreach($plugins as $pluginName => $output)
        {
            $plugin = $pluginManager->getPlugin($pluginName);
            if($plugin !== null && $plugin instanceof Plugin)
            {
                return $plugin;
            }
        }

        return null;
    }


    /**
     * @param Command $command
     *
     * Registers a command to the command map.
     */
    public static function registerCommand(Command $command)
    {
        $server = Server::getInstance();
        $server->getCommandMap()->register($command->getName(), $command);
    }

    /**
     * @return string
     *
     * Gets the prefix used for messages.
     */
    public static function getPrefix()
    {
        return TextFormat::DARK_GRAY . TextFormat::BOLD . "[" . TextFormat::BLUE . "MHMain" . TextFormat::DARK_GRAY . "]" . TextFormat::RESET;
    }

    /**
     * @param $message
     * @return bool|null
     *
     * Turns a string to a boolean.
     */
    public static function stringToBool($message) {

        if(is_bool($message)) {
            return (bool)$message;
        }

        $message = strtolower($message);

        switch($message) {
            case "on":
            case 1:
            case "true":
            case "enabled":
                return true;
            case "off":
            case 0:
            case "false":
            case "disabled":
                return false;
        }

        return null;
    }
}