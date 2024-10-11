<?php

declare(strict_types=1);

namespace wavycraft\blockannouncer\event;

use pocketmine\event\Listener;
use pocketmine\event\block\BlockBreakEvent;

use pocketmine\block\BlockTypeIds;

use function array_map;
use function constant;
use function strtoupper;
use function str_replace;
use function in_array;

use wavycraft\blockannouncer\Loader;

class EventListener implements Listener {

    private $plugin;
    private array $targetBlocks;
    private array $targetWorlds;
    private bool $enabledAllWorlds;

    public function __construct() {
        $this->plugin = Loader::getInstance();
        $config = $this->plugin->getConfig();

        $this->targetBlocks = array_map(static fn($block) => constant(BlockTypeIds::class . '::' . strtoupper($block)), $config->get("blocks"));
        $this->targetWorlds = $config->get("worlds");
        $this->enabledAllWorlds = $config->get("enabled.all.worlds");
    }

    public function onBlockBreak(BlockBreakEvent $event) {
        $player = $event->getPlayer();
        $block = $event->getBlock();
        $world = $player->getWorld()->getFolderName();

        if (!$this->enabledAllWorlds && !in_array($world, $this->targetWorlds, true)) {
            return;
        }

        if (in_array($block->getTypeId(), $this->targetBlocks, true)) {
            $blockName = $block->getName();
            $playerName = $player->getName();

            $this->plugin->getServer()->broadcastMessage(str_replace(["{player}", "{block}"], [$playerName, $blockName], $this->plugin->getConfig()->get("broadcast.message")));
        }
    }
}