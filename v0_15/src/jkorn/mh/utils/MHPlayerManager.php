<?php
/**
 * Created by PhpStorm.
 * User: jkorn2324
 * Date: 2020-05-06
 * Time: 22:47
 */

declare(strict_types=1);

namespace jkorn\mh\utils;


use jkorn\mh\MHMain;
use pocketmine\Player;
use pocketmine\Server;

class MHPlayerManager
{

    /** @var array|string[] */
    private $playerCooldowns;

    /** @var int - Cooldown in seconds. */
    private $cooldown = 60;

    /** @var Server */
    private $server;

    public function __construct(MHMain $main, array $contents)
    {
        $this->playerCooldowns = [];
        $this->server = $main->getServer();

        $this->loadCooldownTime($contents);
    }


    /**
     * @param array $contents
     *
     * Loads the cooldown time from the file.
     */
    private function loadCooldownTime(array $contents)
    {
        if(isset($contents["meetupCommandCooldown"])) {
            $this->cooldown = (int)$contents["meetupCommandCooldown"];
        }
    }

    /**
     * @param array &$data
     *
     * Saves the data to the array.
     */
    public function saveData(array &$data)
    {
        $data["meetupCommandCooldown"] = $this->cooldown;
    }

    /**
     * @param Player $player
     * @return bool
     *
     * Puts the player in a cooldown.
     */
    public function putInCooldown(Player $player)
    {
        if(isset($this->playerCooldowns[$cid = $player->getClientId()])) {
            return false;
        }

        $this->playerCooldowns[$cid] = $this->cooldown;
        return true;
    }

    /**
     * @param Player $player
     * @return bool
     *
     * Determines if player is in cooldown.
     */
    public function isInCooldown(Player $player)
    {
        return isset($this->playerCooldowns[$player->getClientId()]);
    }

    /**
     * @param Player $player
     *
     * Removes the player from the cooldown.
     */
    public function removeFromCooldown(Player $player) {
        if(isset($this->playerCooldowns[$player->getClientId()])) {
            unset($this->playerCooldowns[$player->getClientId()]);
        }
    }

    /**
     * Updates the cooldown information.
     */
    public function updateCooldown()
    {
        foreach($this->playerCooldowns as $cid => $cooldownSeconds) {
            $this->playerCooldowns[$cid] = --$cooldownSeconds;
            if($cooldownSeconds <= 0) {
                unset($this->playerCooldowns[$cid]);
            }
        }
    }

    /**
     * @return int
     *
     * Gets the cooldown.
     */
    public function getCooldown()
    {
        return $this->cooldown;
    }
}