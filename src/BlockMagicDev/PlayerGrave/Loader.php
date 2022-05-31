<?php

/*
 *
 *  ____  _            _    __  __             _
 * |  _ \| |          | |  |  \/  |           (_)
 * | |_) | | ___   ___| | _| \  / | __ _  __ _ _  ___
 * |  _ <| |/ _ \ / __| |/ / |\/| |/ _` |/ _` | |/ __|
 * | |_) | | (_) | (__|   <| |  | | (_| | (_| | | (__
 * |____/|_|\___/ \___|_|\_\_|  |_|\__,_|\__, |_|\___|
 *                                       __/ |
 *                                      |___/
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * @author BlockMagicDev
 * @link https://github.com/BlockMagicDev/PlayerGrave
 *
 *
*/

declare(strict_types=1);

namespace BlockMagicDev\PlayerGrave;

use BlockMagicDev\PlayerGrave\Entity\Grave;
use BlockmagicDev\PlayerGrave\GraveManager;
use pocketmine\entity\EntityDataHelper;
use pocketmine\entity\EntityFactory;
use pocketmine\entity\Human;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\SingletonTrait;
use pocketmine\world\World;
use function in_array;

class Loader extends PluginBase implements Listener {
	use SingletonTrait;

	protected function onEnable() : void {
		self::setInstance($this);
		$this->saveResource("geometry.json");
		$this->saveResource("Grave.png");
		$this->saveDefaultConfig();
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
		EntityFactory::getInstance()->register(Grave::class, function(World $world, CompoundTag $nbt) : Grave {
			return new Grave(EntityDataHelper::parseLocation($nbt, $world), Human::parseSkinNBT($nbt), $nbt);
		}, ['Grave']);
	}

	protected function onDisable() : void {
		foreach ($this->getServer()->getWorldManager()->getWorlds() as $worlds) {
			foreach ($worlds->getEntities() as $entities) {
				if ($entities instanceof Grave) {
					$entities->close();
				}
			}
		}
	}

	public function onDealth(PlayerDeathEvent $event) : void {
		$player = $event->getPlayer();
		if ($player->isOnGround() and !$player->isUnderwater()) {
			if ($this->isAllowWorld($player->getWorld())) {
				$this->getGraveManager()->createGrave($player);
			}
		}
	}

	public function isShowMemorial() : bool {
		return $this->getConfig()->get("show-memorial-name", true);
	}

	public function isLimitSpawn() : bool {
		return $this->getConfig()->get("limit-spawn", true);
	}

	public function isDespawn() : bool {
		return $this->getConfig()->get("despawn", true);
	}

	public function getDespawnTime() : bool {
		return $this->getConfig()->get("despawn-time", 40);
	}

	public function isAllowWorld(World $world) : bool {
		return empty($this->getConfig()->get("worlds")) or in_array($world->getFolderName(), $this->getConfig()->get("worlds"), true);
	}

	public function getGraveManager() : GraveManager {
		return new GraveManager();
	}
}