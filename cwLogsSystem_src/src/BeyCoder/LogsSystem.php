<?php

namespace BeyCoder;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerCommandPreprocessEvent;
use pocketmine\plugin\PluginBase;

class LogsSystem extends PluginBase implements Listener {

    public $commands = [
        "tp", "ban", "pardon", "ban-ip", "pardon-ip", "bal", "mymoney", "pay", "tpaccept", "tpa", "home", "sethome", "warp", "setwarp", "event", "list", "spawn", "freefly", "company", "sleep", "gm", "rg"
    ];

    public $playerCanLog = [];

    public function onEnable(){
        $this->getLogger()->info("Плагин успешно включён и функциониурет!");
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
    }

    public function onCommandUse(PlayerCommandPreprocessEvent $event){
        $message = $event->getMessage();
        $player = $event->getPlayer();
        if($message[0] == "/"){
            $logger = new CommandLog($this, $this->commands, $message, $player);
            $logger->sendLog();
        }
    }

    public function onCommand(CommandSender $sender, Command $command, $label, array $args)
    {
        $name = strtolower($sender->getName());

        switch ($command->getName()){
            case "log":
                if($sender->hasPermission("api.logger")){
                    if(in_array($name, $this->playerCanLog)){
                        unset($this->playerCanLog[array_search($name, $this->playerCanLog)]);
                        $sender->sendMessage("§c>< §fРежим логов §cвыключен§c!");

                    }else{
                        $this->playerCanLog[] = $name;
                        $sender->sendMessage("§c>< §fРежим логов §aвключён§c!");
                    }
                }else{
                    $sender->sendMessage("§c>< §fВключать режим логов§с, §fможно с привилегии §e§lСпонсор §rи выше§c!");
                }
                break;
        }
    }

}