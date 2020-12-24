<?php

declare(strict_types=1);

namespace BlockHorizons\Fireworks\entity\animation;

use BlockHorizons\Fireworks\entity\FireworksRocket;
use pocketmine\entity\animation\Animation;
use pocketmine\network\mcpe\protocol\ActorEventPacket;

class FireworkParticleAnimation implements Animation
{
	/** @var FireworksRocket */
	private $firework;

	public function __construct(FireworksRocket $firework)
	{
		$this->firework = $firework;
	}

	public function encode(): array
	{
		return [
			ActorEventPacket::create($this->firework->getId(), ActorEventPacket::FIREWORK_PARTICLES, 0)
		];
	}
}