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

    public function __construct(MHMain $main, array $contents)
    {
        $this->main = $main;
        $this->muted = false;

        $this->loadData($contents);
    }

    /**
     * Loads the data from the json file.
     * @param array $contents
     */
    private function loadData(array $contents) {

        if(isset($contents["muted"])) {
            $this->muted = (bool)$contents["muted"];
        }
    }

    /**
     * Saves the data to the json file
     *
     * @param array &$data
     */
    public function saveData(array &$data) {

        $data["muted"] = $this->muted;
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