<?php

namespace BlockMagicDev\PlayerGrave\Task;

use pocketmine\entity\Entity;
use pocketmine\scheduler\CancelTaskException;
use pocketmine\scheduler\Task;

class KillTask extends Task{

    protected Entity $entity;

    public function __construct(Entity $entity)
    {
        $this->entity = $entity;
    }

    public function onRun(): void
    {
        $this->entity->close();
        if($this->entity->isClosed()){
            throw new CancelTaskException();
        }
    }
}