<?php
declare(strict_types=1);

namespace BlockHorizons\Fireworks;

use BlockHorizons\Fireworks\entity\FireworksRocket;
use BlockHorizons\Fireworks\item\Fireworks;
use pocketmine\data\bedrock\EntityLegacyIds;
use pocketmine\entity\EntityDataHelper;
use pocketmine\entity\EntityFactory;
use pocketmine\item\ItemFactory;
use pocketmine\item\ItemIdentifier;
use pocketmine\item\ItemIds;
use pocketmine\item\VanillaItems;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\network\mcpe\protocol\types\entity\EntityIds;
use pocketmine\plugin\PluginBase;
use pocketmine\world\World;

class Loader extends PluginBase
{
    public function onEnable(): void
    {
        $item = new Fireworks(new ItemIdentifier(ItemIds::FIREWORKS, 0), "Fireworks");
        ItemFactory::getInstance()->register($item, true);
        EntityFactory::getInstance()->register(FireworksRocket::class, static function (World $world, CompoundTag $nbt) use ($item): FireworksRocket {
            return new FireworksRocket(EntityDataHelper::parseLocation($nbt, $world), $item);
        }, ["FireworksRocket", EntityIds::FIREWORKS_ROCKET], EntityLegacyIds::FIREWORKS_ROCKET);
    }
}