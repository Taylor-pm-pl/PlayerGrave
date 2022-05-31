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
use BlockMagicDev\PlayerGrave\Loader;
use BlockMagicDev\PlayerGrave\Task\KillTask;
use pocketmine\entity\Entity;
use pocketmine\entity\Skin;
use pocketmine\math\Vector3;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\DoubleTag;
use pocketmine\nbt\tag\FloatTag;
use pocketmine\nbt\tag\ListTag;
use pocketmine\player\Player;
use pocketmine\world\Position;
use function array_search;
use function chr;
use function file_get_contents;
use function getimagesize;
use function imagecolorat;
use function imagecreatefrompng;
use function imagedestroy;
use function in_array;
use function intval;

class GraveManager {
	protected static array $list;

	public function __construct() {
		//NOTHING
	}

	public function createGrave(Player $player) : void {
		$nbt = $this->createBaseNBT($player->getPosition());
		$nbt->setString("Memorial", $player->getName());
		$grave = new Grave($player->getLocation(), $this->getSkin(), $nbt);
		if (Loader::getInstance()->isShowMemorial()) {
			$grave->setNameTag($player->getName());
			$grave->setNameTagAlwaysVisible(true);
		} else {
			$grave->setNameTagAlwaysVisible(false);
		}
		if (Loader::getInstance()->isLimitSpawn()) {
			if (isset(GraveManager::$list[$player->getName()])) {
				return;
			}
			GraveManager::$list[$player->getName()] = $grave->getId();
		}
		if (Loader::getInstance()->isDespawn()) {
			Loader::getInstance()->getScheduler()->scheduleRepeatingTask(new KillTask($grave), Loader::getInstance()->getDespawnTime() * 20);
		}
		$grave->spawnToAll();
	}

	public function getSkin() : Skin {
		$loader = Loader::getInstance();
		$path = $loader->getDataFolder() . "Grave.png";
		$img = @imagecreatefrompng($path);
		$skinbytes = "";
		$s = intval(@getimagesize($path)[1]);
		for ($y = 0; $y < $s; $y++) {
			for ($x = 0; $x < 64; $x++) {
				$colorat = @imagecolorat($img, $x, $y);
				$a = ((~((int) ($colorat >> 24))) << 1) & 0xff;
				$r = ($colorat >> 16) & 0xff;
				$g = ($colorat >> 8) & 0xff;
				$b = $colorat & 0xff;
				$skinbytes .= chr($r) . chr($g) . chr($b) . chr($a);
			}
		}
		@imagedestroy($img);
		return new Skin("Standard_CustomSlim", $skinbytes, "", "geometry.grave", file_get_contents($loader->getDataFolder() . "geometry.json"));
	}

	public function createBaseNBT(Position $pos, ?Vector3 $motion = null, float $yaw = 0.0, float $pitch = 0.0) : CompoundTag {
		return CompoundTag::create()
			->setTag("Pos", new ListTag([
				new DoubleTag($pos->x),
				new DoubleTag($pos->y),
				new DoubleTag($pos->z)
			]))
			->setTag("Motion", new ListTag([
				new DoubleTag($motion !== null ? $motion->x : 0.0),
				new DoubleTag($motion !== null ? $motion->y : 0.0),
				new DoubleTag($motion !== null ? $motion->z : 0.0)
			]))
			->setTag("Rotation", new ListTag([
				new FloatTag($yaw),
				new FloatTag($pitch)
			]));
	}

	public function inList(Entity $entity) : bool {
		return in_array($entity->getId(), $this->list, true);
	}

	public function deleteFromList(Entity $entity) : void {
		$array_search = array_search($entity->getId(), GraveManager::$list, true);
		unset(GraveManager::$list[$array_search]);
	}
}