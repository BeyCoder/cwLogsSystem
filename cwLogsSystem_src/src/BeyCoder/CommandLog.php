<?php

/**
 * @author BeyCoder
 * @link https://github.com/BeyCoder/
 */

namespace BeyCoder;

use pocketmine\Player;
use pocketmine\Server;

class CommandLog
{
    /**
     * @var LogsSystem
     */
    private $logsSystem;

    /**
     * @var string
     */
    private $command;

    /**
     * @var Player
     */
    private $commandSender;

    /**
     * @var array
     */
    private $loggerCommands;

    /**
     * @var bool
     */
    private $isLocked;

    /**
     * CommandLog constructor.
     *
     * @param LogsSystem $logsSystem
     * @param array $loggerCommands
     * @param string $command
     * @param Player $commandSender
     */
    public function __construct(LogsSystem $logsSystem, array $loggerCommands, string $command, Player $commandSender)
    {
        $command = ltrim($command, "/");
        $this->logsSystem = $logsSystem;
        $this->loggerCommands = $loggerCommands;
        $this->command = $command;
        $this->commandSender = $commandSender;

        $this->isLocked = $this->canLog();
    }

    /** @sectionStart Private functions section start*/
    /**
     * @return bool
     */
    private function canLog(){
        foreach ($this->loggerCommands as $command){
            if(isset(explode($command, strtolower($this->command))[1])) return false;
        }

        return true;
    }

    /**
     * @return Server
     */
    private function getServer(){
        return $this->logsSystem->getServer();
    }

    /**
     * @param Player $player
     * @return bool
     */
    private function playerCanAcceptLog(Player $player){
        $name = strtolower($player->getName());

        return in_array($name, $this->logsSystem->playerCanLog);
    }
    /** @sectionEnd Private functions section end */



    /** @sectionStart Public functions section start */
    /**
     * @return bool
     */
    public function isLocked(){
        return $this->isLocked;
    }

    /**
     * @return string
     */
    public function getCommand(){
        return $this->command;
    }

    /**
     * @return Player
     */
    public function getCommandSender(){
        return $this->commandSender;
    }

    public function sendLog(){
        if($this->isLocked()) return;

        if(!$this->getCommandSender()->hasPermission("api.logger.moder")) {
            foreach ($this->getServer()->getOnlinePlayers() as $player) {
                if($this->getCommandSender()->hasPermission("api.logger") and strtolower($this->getCommandSender()->getName()) != strtolower($player->getName()) and $this->playerCanAcceptLog($player)){
                    $player->sendMessage("§7[" . date("d:m:Y H:i:s") . "] §d§lИгрок §b§o" . $this->getCommandSender()->getDisplayName() . " §r§8(" . $this->getCommandSender()->getName() . ") §l§dиспользовал команду §e/" . $this->getCommand());
                }
            }
        }
    }
    /** @sectionEnd Public functions section end */
}