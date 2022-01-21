<?php

namespace DayKoala;

use pocketmine\event\Listener;

use pocketmine\event\inventory\InventoryTransactionEvent;

use pocketmine\event\player\PlayerChatEvent;

use DayKoala\inventory\WindowFactory;

class WindowyListener implements Listener{

    protected $plugin;

    public function __construct(Windowy $plugin){
        $this->plugin = $plugin;
    }

    public function onTransaction(InventoryTransactionEvent $event){

    }

    public function onChat(PlayerChatEvent $event){
        $player = $event->getPlayer();

        $window = WindowFactory::getInstance()->get($player, WindowFactory::CHEST);

        $player->setCurrentWindow($window);

    }

}