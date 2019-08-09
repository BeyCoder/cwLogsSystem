<?php

namespace BeyCoder;

use pocketmine\plugin\PluginBase;

class LogsSystem extends PluginBase{

    public function onEnable(){
        $this->getLogger()->info("Плагин успешно включён!");
    }

}