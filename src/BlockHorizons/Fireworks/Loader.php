<?php

declare(strict_types = 1);

namespace BlockHorizons\Fireworks;

use BlockHorizons\Fireworks\item\Fireworks;
use pocketmine\data\bedrock\item\ItemTypeNames;
use pocketmine\data\bedrock\item\SavedItemData;
use pocketmine\item\ItemIdentifier;
use pocketmine\item\ItemTypeIds;
use pocketmine\plugin\PluginBase;
use pocketmine\world\format\io\GlobalItemDataHandlers;

class Loader extends PluginBase
{

	public function onEnable(): void
	{
		$fireworks = new Fireworks(new ItemIdentifier(ItemTypeIds::newId()), "Fireworks");
		GlobalItemDataHandlers::getSerializer()->map($fireworks, static fn() => new SavedItemData(ItemTypeNames::FIREWORK_ROCKET));
		GlobalItemDataHandlers::getDeserializer()->map(ItemTypeNames::FIREWORK_ROCKET, static fn() => clone $fireworks);
	}
}