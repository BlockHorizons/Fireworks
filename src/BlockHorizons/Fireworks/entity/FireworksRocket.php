<?php

declare(strict_types = 1);

namespace BlockHorizons\Fireworks\entity;

use BlockHorizons\Fireworks\item\Fireworks;
use pocketmine\entity\Entity;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\network\mcpe\protocol\ActorEventPacket;
use pocketmine\network\mcpe\protocol\LevelSoundEventPacket;
use pocketmine\network\mcpe\protocol\types\entity\EntityLegacyIds;
use pocketmine\world\World;

class FireworksRocket extends Entity {

	public const DATA_FIREWORK_ITEM = 16; //firework item

	public static function getNetworkTypeId() : int{
		return EntityLegacyIds::FIREWORKS_ROCKET;
	}

	public $width = 0.25;
	public $height = 0.25;

	/** @var int */
	protected $lifeTime = 0;

	public function __construct(World $world, CompoundTag $nbt, ?Fireworks $fireworks = null){
		parent::__construct($world, $nbt);

		if($fireworks !== null && $fireworks->getNamedTag()->getCompoundTag("Fireworks") !== null) {
            $this->networkProperties->setCompoundTag(self::DATA_FIREWORK_ITEM, $fireworks->getNamedTag());
			$this->setLifeTime($fireworks->getRandomizedFlightDuration());
		}

		$packet = new LevelSoundEventPacket();
		$packet->sound = LevelSoundEventPacket::SOUND_LAUNCH;
		$packet->position = $this->location->asVector3();
       	$world->broadcastPacketToViewers($this->location, $packet);
	}

	protected function tryChangeMovement(): void {
		$this->motion->x *= 1.15;
		$this->motion->y += 0.04;
		$this->motion->z *= 1.15;
	}

	public function entityBaseTick(int $tickDiff = 1): bool {
		if($this->closed) {
			return false;
		}

		$hasUpdate = parent::entityBaseTick($tickDiff);
		if($this->doLifeTimeTick()) {
			$hasUpdate = true;
		}

		return $hasUpdate;
	}

	public function setLifeTime(int $life): void {
		$this->lifeTime = $life;
	}

	protected function doLifeTimeTick(): bool {
		if(!$this->isFlaggedForDespawn() and --$this->lifeTime < 0) {
			$this->doExplosionAnimation();
			$this->flagForDespawn();
			return true;
		}

		return false;
	}

	protected function doExplosionAnimation(): void {
		$viewers = $this->getViewers();
		if(count($viewers) > 0){
			$this->server->broadcastPackets($viewers, [ActorEventPacket::create($this->id, ActorEventPacket::FIREWORK_PARTICLES, 0)]);
		}
	}
}