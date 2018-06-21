<?php

declare(strict_types = 1);

namespace BlockHorizons\Fireworks\entity;

use BlockHorizons\Fireworks\item\Fireworks;

use pocketmine\entity\Entity;
use pocketmine\level\Level;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\network\mcpe\protocol\EntityEventPacket;
use pocketmine\network\mcpe\protocol\LevelSoundEventPacket;

class FireworksRocket extends Entity {

	public const NETWORK_ID = Entity::FIREWORKS_ROCKET;

	public const DATA_FIREWORK_ITEM = 16; //firework item

	public $width = 0.25;
	public $height = 0.25;

	/** @var int */
	protected $lifeTime = 0;

	public function __construct(Level $level, CompoundTag $nbt, ?Fireworks $fireworks = null){
		parent::__construct($level, $nbt);

		if($fireworks !== null && $fireworks->getNamedTagEntry("Fireworks") instanceof CompoundTag) {
			$this->propertyManager->setItem(self::DATA_FIREWORK_ITEM, $fireworks);
			$this->setLifeTime($fireworks->getRandomizedFlightDuration());
		}

       	$level->broadcastLevelSoundEvent($this, LevelSoundEventPacket::SOUND_LAUNCH);
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
		$fireworks = $this->propertyManager->getItem(self::DATA_FIREWORK_ITEM);
		if($fireworks === null) {
			return;
		}

		$fireworks_nbt = $fireworks->getNamedTag()->getCompoundTag("Fireworks");
		if($fireworks_nbt === null) {
			return;
		}

		$explosions = $fireworks_nbt->getListTag("Explosions");
		if($explosions === null) {
			return;
		}

		/** @var CompoundTag $explosion */
		foreach($explosions->getAllValues() as $explosion) {
			switch($explosion->getByte("FireworkType")) {
				case Fireworks::TYPE_SMALL_SPHERE:
					$this->level->broadcastLevelSoundEvent($this, LevelSoundEventPacket::SOUND_BLAST);
					break;
				case Fireworks::TYPE_HUGE_SPHERE:
					$this->level->broadcastLevelSoundEvent($this, LevelSoundEventPacket::SOUND_LARGE_BLAST);
					break;
				case Fireworks::TYPE_STAR:
				case Fireworks::TYPE_BURST:
				case Fireworks::TYPE_CREEPER_HEAD:
					$this->level->broadcastLevelSoundEvent($this, LevelSoundEventPacket::SOUND_TWINKLE);
					break;
			}
		}

		$this->broadcastEntityEvent(EntityEventPacket::FIREWORK_PARTICLES);
	}
}