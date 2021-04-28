<?php

declare(strict_types = 1);

namespace BlockHorizons\Fireworks;

use BlockHorizons\Fireworks\entity\FireworksRocket;
use BlockHorizons\Fireworks\item\Fireworks;
use pocketmine\entity\Entity;
use pocketmine\item\Item;
use pocketmine\item\ItemFactory;
use pocketmine\plugin\PluginBase;

class Loader extends PluginBase {

	public function onEnable(): void
	{
		ItemFactory::registerItem(new Fireworks(), true);
		ItemFactory::registerItem(new Item(Item::FIREWORKS_CHARGE, 0, "Fireworks Charge"), true);
		Item::initCreativeItems(); //will load firework rockets from pocketmine's resources folder
		if (!Entity::registerEntity(FireworksRocket::class, false, ["FireworksRocket", "minecraft:fireworks_rocket"])) {
			$this->getLogger()->error("Failed to register FireworksRocket entity with savename 'FireworksRocket'");
		}
	}
}
