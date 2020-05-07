<?php

declare(strict_types=1);

namespace jkorn\mh;


use jkorn\mh\commands\GlobalMute;
use jkorn\mh\commands\TPMeetup;
use jkorn\mh\utils\MHChatManager;
use jkorn\mh\utils\MHPlayerManager;
use pocketmine\plugin\PluginBase;

class MHMain extends PluginBase
{

    /** @var MHMain */
    private static $instance;

    /** @var MHChatManager */
    private static $chatManager;
    /** @var MHPlayerManager */
    private static $playerManager;

    /** @var string */
    private $pluginFile = "";

    /**
     * Called when the plugin is enabled.
     */
    public function onEnable()
    {
        self::$instance = $this;

        $this->initDataFolder();
        $this->registerCommands();

        $contents = json_decode(file_get_contents($this->pluginFile), true) ?? [];

        self::$chatManager = new MHChatManager($this, $contents);
        self::$playerManager = new MHPlayerManager($this, $contents);

        new MHListener($this);
        new MHTask($this);

        $this->getLogger()->info("MHMain is now enabled!");
    }

    /**
     * Called when the plugin is disabled.
     */
    public function onDisable()
    {
        $contents = [];
        self::$chatManager->saveData($contents);
        self::$playerManager->saveData($contents);

        file_put_contents($this->pluginFile, json_encode($contents));

        $this->getLogger()->info("MHMain is now disabled!");
    }

    /**
     * Initializes the data folder.
     */
    private function initDataFolder() {

        if(!is_dir($dataFolder = $this->getDataFolder())) {
            mkdir($dataFolder);
        }

        $this->pluginFile = $this->getDataFolder() . "plugin_data.json";

        if(!file_exists($this->pluginFile)) {
            $file = fopen($this->pluginFile, "w");
            fclose($file);
        }
    }

    /**
     * @return string
     *
     * Gets the plugin's file.
     */
    public function getPluginFile()
    {
        return $this->pluginFile;
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
     * @return MHPlayerManager
     *
     * Gets the player manager.
     */
    public static function getPlayerManager() {
        return self::$playerManager;
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