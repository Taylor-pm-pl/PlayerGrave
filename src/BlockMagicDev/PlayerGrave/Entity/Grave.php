<?php

namespace BlockMagicDev\PlayerGrave\Entity;

use pocketmine\entity\Human;
use pocketmine\entity\Location;
use pocketmine\entity\Skin;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\nbt\tag\CompoundTag;

class Grave extends Human {

    private string $Memorial;

    public function __construct(Location $location, Skin $skin, CompoundTag $nbt = null)
    {
        parent::__construct($location, $skin, $nbt);   
    }

    public function saveNBT(): CompoundTag
    {
        return parent::saveNBT()
        ->setString("Memorial", $this->Memorial);
    }

    public function initEntity(CompoundTag $nbt): void{
        $this->Memorial = $nbt->getString("Memorial");
        $this->setHealth(20);
        $this->setScale(1.5);
        $this->setSkin($this->skin);
        parent::initEntity($nbt);
    }

    public function attack(EntityDamageEvent $source): void
    {
        $source->cancel();
    }
}