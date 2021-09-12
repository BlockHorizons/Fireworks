# Fireworks
Adds fireworks to your PocketMine server<br>
Download the compiled `.phar` format of Fireworks from the [Poggit CI](https://poggit.pmmp.io/ci/BlockHorizons/Fireworks).
## API
### Adding firework items to a player's inventory
Giving players fireworks is easy as pie. Here are some examples (where `$player` is a `\pocketmine\player\Player`
object):
- **Base firework**
```php
/** @var Fireworks $fw */
$fw = ItemFactory::getInstance()->get(ItemIds::FIREWORKS);
$player->getInventory()->addItem($fw);
```
- **Sphere firework with color fade from blue to cyan**
```php
/** @var Fireworks $fw */
$fw = ItemFactory::getInstance()->get(ItemIds::FIREWORKS);

// addExplosion Parameters:
// int $type: Type of explosion, 0 - 4, see Fireworks::TYPE_* constants
// string $color: Color of explosion, see Fireworks::COLOR_* constants
// string $fade = "": Color to fade to, none if an empty string is passed
// bool $flicker = false: If the particles should flicker
// bool $trail = false: If the particles leave a trail behind
$fw->addExplosion(Fireworks::TYPE_SMALL_SPHERE, Fireworks::COLOR_BLUE, Fireworks::COLOR_DARK_AQUA, false, false);

$player->getInventory()->addItem($fw);
```
- **Green creeper firework, flying higher**
```php
/** @var Fireworks $fw */
$fw = ItemFactory::getInstance()->get(ItemIds::FIREWORKS);
$fw->addExplosion(Fireworks::TYPE_CREEPER_HEAD, Fireworks::COLOR_GREEN, "", false, false);
$fw->setFlightDuration(2);
$player->getInventory()->addItem($fw);
```
- **High flying flashing star firework with trail**
```php
/** @var Fireworks $fw */
$fw = ItemFactory::getInstance()->get(ItemIds::FIREWORKS);
$fw->addExplosion(Fireworks::TYPE_STAR, Fireworks::COLOR_YELLOW, "", true, true);
$fw->setFlightDuration(3);
$player->getInventory()->addItem($fw);
```
- **All-colored sphere firework with trail**
```php
/** @var Fireworks $fw */
$fw = ItemFactory::getInstance()->get(ItemIds::FIREWORKS);
$fw->addExplosion(Fireworks::TYPE_SMALL_SPHERE, Fireworks::COLOR_BLACK, "", false, true);
$fw->addExplosion(Fireworks::TYPE_SMALL_SPHERE, Fireworks::COLOR_RED, "", false, true);
$fw->addExplosion(Fireworks::TYPE_SMALL_SPHERE, Fireworks::COLOR_DARK_GREEN, "", false, true);
$fw->addExplosion(Fireworks::TYPE_SMALL_SPHERE, Fireworks::COLOR_BROWN, "", false, true);
$fw->addExplosion(Fireworks::TYPE_SMALL_SPHERE, Fireworks::COLOR_BLUE, "", false, true);
$fw->addExplosion(Fireworks::TYPE_SMALL_SPHERE, Fireworks::COLOR_DARK_PURPLE, "", false, true);
$fw->addExplosion(Fireworks::TYPE_SMALL_SPHERE, Fireworks::COLOR_DARK_AQUA, "", false, true);
$fw->addExplosion(Fireworks::TYPE_SMALL_SPHERE, Fireworks::COLOR_GRAY, "", false, true);
$fw->addExplosion(Fireworks::TYPE_SMALL_SPHERE, Fireworks::COLOR_DARK_GRAY, "", false, true);
$fw->addExplosion(Fireworks::TYPE_SMALL_SPHERE, Fireworks::COLOR_PINK, "", false, true);
$fw->addExplosion(Fireworks::TYPE_SMALL_SPHERE, Fireworks::COLOR_GREEN, "", false, true);
$fw->addExplosion(Fireworks::TYPE_SMALL_SPHERE, Fireworks::COLOR_YELLOW, "", false, true);
$fw->addExplosion(Fireworks::TYPE_SMALL_SPHERE, Fireworks::COLOR_LIGHT_AQUA, "", false, true);
$fw->addExplosion(Fireworks::TYPE_SMALL_SPHERE, Fireworks::COLOR_DARK_PINK, "", false, true);
$fw->addExplosion(Fireworks::TYPE_SMALL_SPHERE, Fireworks::COLOR_GOLD, "", false, true);
$fw->addExplosion(Fireworks::TYPE_SMALL_SPHERE, Fireworks::COLOR_WHITE, "", false, true);
$player->getInventory()->addItem($fw);
```
### Launching fireworks
Fireworks can be launched after you created the firework item.
This example spawns a green creeper firework at the default world's spawn
```php
// Create the type of firework item to be launched
/** @var Fireworks $fw */
$fw = ItemFactory::getInstance()->get(ItemIds::FIREWORKS);
$fw->addExplosion(Fireworks::TYPE_CREEPER_HEAD, Fireworks::COLOR_GREEN, "", false, false);
$fw->setFlightDuration(2);

// Use whatever level you'd like here. Must be loaded
$world = Server::getInstance()->getWorldManager()->getDefaultWorld();
// Choose some coordinates
$vector3 = $world->getSpawnLocation()->add(0.5, 1, 0.5);
// Create the NBT data
$nbt = FireworksRocket::createBaseNBT($vector3, new Vector3(0.001, 0.05, 0.001), lcg_value() * 360, 90);
// Construct and spawn
$entity = FireworksRocket::createEntity("FireworksRocket", $world, $nbt, $fw);
if ($entity instanceof FireworksRocket) {
    $entity->spawnToAll();
}
```
