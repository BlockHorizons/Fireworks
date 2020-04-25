<?php

declare(strict_types = 1);

namespace BlockHorizons\Fireworks;

use BlockHorizons\Fireworks\item\Fireworks;
use BlockHorizons\Fireworks\entity\FireworksRocket;

use pocketmine\entity\EntityFactory;
use pocketmine\item\ItemFactory;
use pocketmine\plugin\PluginBase;

class Loader extends PluginBase {

	protected function onEnable(): void {
		ItemFactory::getInstance()->register(new Fireworks(), true);
		EntityFactory::getInstance()->register(FireworksRocket::class, ["FireworksRocket"]);
	}
}