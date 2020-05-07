<?php

declare(strict_types=1);

namespace jkorn\mh\utils;


use jkorn\mh\MHMain;

class MHChatManager
{

    /** @var bool */
    private $muted;
    /** @var MHMain */
    private $main;

    /** @var string */
    private $jsonFile;

    public function __construct(MHMain $main)
    {
        $this->main = $main;
        $this->muted = false;

        $this->jsonFile = $main->getDataFolder() . "chat_data.json";
        $this->loadData();
    }

    /**
     * Loads the data from the json file.
     */
    private function loadData() {

        if(!file_exists($this->jsonFile)) {

            $file = fopen($this->jsonFile, "w");
            fclose($file);

            file_put_contents($this->jsonFile, json_encode(["muted" => $this->muted]));
            return;
        }

        $contents = json_decode(file_get_contents($this->jsonFile), true);
        if(isset($contents["muted"])) {
            $this->muted = (bool)$contents["muted"];
        }
    }

    /**
     * Saves the data to the json file
     */
    public function saveData() {

        file_put_contents($this->jsonFile, json_encode(["muted" => $this->muted]));
    }

    /**
     * @return bool
     *
     * Determines if the chat is muted.
     */
    public function isMuted()
    {
        return $this->muted;
    }

    /**
     * @param bool $muted
     * @return bool
     *
     * Sets the chat as muted or not.
     */
    public function setMuted(bool $muted) {
        $output = $this->muted != $muted;
        $this->muted = $muted;
        return $output;
    }
}