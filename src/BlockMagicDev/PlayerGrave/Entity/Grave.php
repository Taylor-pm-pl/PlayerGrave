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

namespace BlockMagicDev\PlayerGrave\Entity;

use pocketmine\entity\Human;
use pocketmine\entity\Location;
use pocketmine\entity\Skin;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\nbt\tag\CompoundTag;

class Grave extends Human {
	private string $Memorial;

	public function __construct(Location $location, Skin $skin, CompoundTag $nbt = null) {
		parent::__construct($location, $skin, $nbt);
	}

	public function saveNBT() : CompoundTag {
		return parent::saveNBT()
		->setString("Memorial", $this->Memorial);
	}

	public function initEntity(CompoundTag $nbt) : void {
		$this->Memorial = $nbt->getString("Memorial");
		$this->setHealth(20);
		$this->setScale(1.5);
		$this->setSkin($this->skin);
		parent::initEntity($nbt);
	}

	public function attack(EntityDamageEvent $source) : void {
		$source->cancel();
	}
}