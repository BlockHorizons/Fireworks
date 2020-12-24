<?php

declare(strict_types = 1);

namespace BlockHorizons\Fireworks\entity;

use BlockHorizons\Fireworks\item\Fireworks;
use FireworkParticleAnimation;
use pocketmine\entity\Entity;
use pocketmine\entity\Location;
use pocketmine\network\mcpe\protocol\LevelSoundEventPacket;
use pocketmine\network\mcpe\protocol\types\entity\EntityIds;
use pocketmine\network\mcpe\protocol\types\entity\EntityMetadataCollection;

class FireworksRocket extends Entity {

	public const DATA_FIREWORK_ITEM = 16; //firework item

	public static function getNetworkTypeId(): string
	{
		return EntityIds::FIREWORKS_ROCKET;
	}

	public $width = 0.25;
	public $height = 0.25;

	/** @var int */
	protected $lifeTime = 0;
	/** @var Fireworks|null */
	protected $fireworks;

	public function __construct(Location $location, ?Fireworks $fireworks = null, ?int $lifeTime = null)
	{
		parent::__construct($location);

		$this->fireworks = $fireworks;

		if ($fireworks !== null && $fireworks->getNamedTag()->getCompoundTag("Fireworks") !== null) {
			$this->setLifeTime($lifeTime ?? $fireworks->getRandomizedFlightDuration());
		}

		$packet = new LevelSoundEventPacket();
		$packet->sound = LevelSoundEventPacket::SOUND_LAUNCH;
		$packet->position = $this->location->asVector3();
		$location->getWorld()->broadcastPacketToViewers($this->location, $packet);
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

	protected function doLifeTimeTick(): bool
	{
		if (--$this->lifeTime < 0 && !$this->isFlaggedForDespawn()) {
			$this->doExplosionAnimation();
			$this->flagForDespawn();
			return true;
		}

		return false;
	}

	protected function doExplosionAnimation(): void
	{
		$this->broadcastAnimation(new FireworkParticleAnimation($this), $this->getViewers());
	}

	public function syncNetworkData(EntityMetadataCollection $properties): void
	{
		parent::syncNetworkData($properties);
		$properties->setCompoundTag(self::DATA_FIREWORK_ITEM, $this->fireworks->getNamedTag());
	}
}