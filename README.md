<p align="center">
  <a href="https://github.com/DayKoala/Windowy/stargazers"><img src="https://i.ibb.co/pzyGrWx/Windowy-Gif.gif"></img></a><br>
</p>
<p align="center">
  <img alt= "Last Commit" src= "https://img.shields.io/github/last-commit/DayKoala/Windowy?color=green">
</p>

# About

- **[Windowy](https://github.com/DayKoala/Windowy)** is a transaction-focused temporary inventory generator made for
**[PocketMine-MP](https://github.com/pmmp/PocketMine-MP)**.

# How to use

- Windowy comes with 4 registered inventories

```php

use DayKoala\inventory\WindowFactory;

$id = WindowFactory::CHEST;
$id = WindowFactory::DOUBLE_CHEST;
$id = WindowFactory::FURNACE;
$id = WindowFactory::HOPPER;

```

- You can get the desired inventory using:

```php

$window = WindowFactory::getInstance()->get($id, $name);

```

- If you want to simplify:

```php

use DayKoala\Windowy;

$window = Windowy::getWindow($id, $name);

```

# Registering inventory

- If you want to register an inventory, use:

```php

use DayKoala\block\BlockEntityMetadata;

$window = new MyWindow(WindowTypes::CONTAINER, 27, new BlockEntityMetadata(Tile::class, BlockLegacyIds::Block));

```

- You can also set a fixed transaction for inventory, before or after registering, using:

```php

use DayKoala\inventory\action\WindowTransaction;

$transaction = function(WindowTransaction $action){
   $player = $action->getPlayer();
   
   $player->sendMessage("I won't let you take this item haha!");
   
   $action->cancel();
};

$window->setTransaction($transaction);

```
