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

namespace BlockMagicDev\PlayerGrave\Task;

use pocketmine\entity\Entity;
use pocketmine\scheduler\CancelTaskException;
use pocketmine\scheduler\Task;

class KillTask extends Task {
	protected Entity $entity;

	public function __construct(Entity $entity) {
		$this->entity = $entity;
	}

	public function onRun() : void {
		$this->entity->close();
		if ($this->entity->isClosed()) {
			throw new CancelTaskException();
		}
	}
}