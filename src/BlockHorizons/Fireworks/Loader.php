<?php

declare(strict_types = 1);

namespace BlockHorizons\Fireworks;

use BlockHorizons\Fireworks\item\Fireworks;
use BlockHorizons\Fireworks\entity\FireworksRocket;

use pocketmine\entity\EntityDataHelper;
use pocketmine\entity\EntityFactory;
use pocketmine\item\ItemFactory;
use pocketmine\item\ItemIdentifier;
use pocketmine\item\ItemIds;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\plugin\PluginBase;
use pocketmine\world\World;

class Loader extends PluginBase {

	protected function onEnable(): void {
		ItemFactory::getInstance()->register(new Fireworks(new ItemIdentifier(ItemIds::FIREWORKS, 0), "Fireworks"), true);
		EntityFactory::getInstance()->register(FireworksRocket::class, static function(World $world, CompoundTag $nbt) : FireworksRocket{
			return new FireworksRocket(EntityDataHelper::parseLocation($nbt, $world));
		}, ["FireworksRocket"]);
	}
}