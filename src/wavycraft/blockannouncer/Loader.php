<?php

declare(strict_types=1);

namespace wavycraft\blockannouncer;

use pocketmine\plugin\PluginBase;

use wavycraft\blockannouncer\event\EventListener;

class Loader extends PluginBase {

    private static Loader $instance;

    protected function onLoad() : void{
        self::$instance = $this;
    }

    protected function onEnable() : void{
        $this->saveDefaultConfig();
        $this->getServer()->getPluginManager()->registerEvents(new EventListener(), $this);
    }

    public static function getInstance() : self{
        return self::$instance;
    }
}