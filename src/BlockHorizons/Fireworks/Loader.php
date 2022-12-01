<?php

declare(strict_types = 1);

namespace BlockHorizons\Fireworks;

use BlockHorizons\Fireworks\entity\FireworksRocket;
use BlockHorizons\Fireworks\item\Fireworks;
use pocketmine\data\bedrock\EntityLegacyIds;
use pocketmine\data\bedrock\item\ItemTypeNames;
use pocketmine\data\bedrock\item\SavedItemData;
use pocketmine\entity\EntityDataHelper;
use pocketmine\entity\EntityFactory;
use pocketmine\item\Item;
use pocketmine\item\ItemIdentifier;
use pocketmine\item\ItemTypeIds;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\network\mcpe\protocol\types\entity\EntityIds;
use pocketmine\plugin\PluginBase;
use pocketmine\world\format\io\GlobalItemDataHandlers;
use pocketmine\world\World;

class Loader extends PluginBase
{

	public function onEnable(): void
	{
		$fireworks = new Fireworks(new ItemIdentifier(ItemTypeIds::newId()), "Fireworks");
		GlobalItemDataHandlers::getSerializer()->map($fireworks, static fn() => new SavedItemData(ItemTypeNames::FIREWORK_ROCKET));
		GlobalItemDataHandlers::getDeserializer()->map(ItemTypeNames::FIREWORK_ROCKET, static fn() => clone $fireworks);
		EntityFactory::getInstance()->register(FireworksRocket::class, static function (World $world, CompoundTag $nbt): FireworksRocket {
			return new FireworksRocket(EntityDataHelper::parseLocation($nbt, $world), Item::nbtDeserialize($nbt->getCompoundTag("Item")));
		}, ["FireworksRocket", EntityIds::FIREWORKS_ROCKET], EntityLegacyIds::FIREWORKS_ROCKET);
	}
}