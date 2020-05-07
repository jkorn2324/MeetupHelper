<?php

declare(strict_types=1);

namespace jkorn\mh;


use jkorn\mh\commands\GlobalMute;
use jkorn\mh\commands\TPMeetup;
use jkorn\mh\utils\MHChatManager;
use pocketmine\plugin\PluginBase;

class MHMain extends PluginBase
{

    /** @var MHMain */
    private static $instance;

    /** @var MHChatManager */
    private static $chatManager;

    /**
     * Called when the plugin is enabled.
     */
    public function onEnable()
    {
        self::$instance = $this;

        $this->initDataFolder();
        $this->registerCommands();

        self::$chatManager = new MHChatManager($this);

        $this->getLogger()->info("MHMain is now enabled!");
    }

    /**
     * Called when the plugin is disabled.
     */
    public function onDisable()
    {
        self::$chatManager->saveData();
        $this->getLogger()->info("MHMain is now disabled!");
    }

    /**
     * Initializes the data folder.
     */
    private function initDataFolder() {
        if(!is_dir($dataFolder = $this->getDataFolder())) {
            mkdir($dataFolder);
        }
    }

    /**
     * @return MHMain
     *
     * Gets the main's instance.
     */
    public static function getInstance()
    {
        return self::$instance;
    }

    /**
     * @return MHChatManager
     *
     * Gets the chat manager.
     */
    public static function getChatManager() {
        return self::$chatManager;
    }

    /**
     * Registers the commands to the plugin.
     */
    private function registerCommands()
    {
        MHUtil::registerCommand(new TPMeetup());
        MHUtil::registerCommand(new GlobalMute());
    }
}