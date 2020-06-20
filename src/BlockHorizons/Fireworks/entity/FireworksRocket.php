<?php

declare(strict_types = 1);

namespace BlockHorizons\Fireworks\entity;

use BlockHorizons\Fireworks\item\Fireworks;
use pocketmine\entity\Entity;
use pocketmine\entity\Location;
use pocketmine\network\mcpe\protocol\ActorEventPacket;
use pocketmine\network\mcpe\protocol\LevelSoundEventPacket;
use pocketmine\network\mcpe\protocol\types\entity\EntityIds;

class FireworksRocket extends Entity {

	public const DATA_FIREWORK_ITEM = 16; //firework item

	public static function getNetworkTypeId() : string{
		return EntityIds::FIREWORKS_ROCKET;
	}

	public $width = 0.25;
	public $height = 0.25;

	/** @var int */
	protected $lifeTime = 0;

	public function __construct(Location $location, ?Fireworks $fireworks = null, ?int $lifeTime = null){
		if($fireworks !== null && $fireworks->getNamedTag()->getCompoundTag("Fireworks") !== null) {
			$this->getNetworkProperties()->setCompoundTag(self::DATA_FIREWORK_ITEM, $fireworks->getNamedTag());
			$this->setLifeTime($lifeTime ?? $fireworks->getRandomizedFlightDuration());
		}

		parent::__construct($location);
		$packet = new LevelSoundEventPacket();
		$packet->sound = LevelSoundEventPacket::SOUND_LAUNCH;
		$packet->position = $this->location->asVector3();
		$location->getWorldNonNull()->broadcastPacketToViewers($this->location, $packet);
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