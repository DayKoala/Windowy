<p align="center">
  <a href="https://github.com/DayKoala/Windowy/stargazers"><img src="https://i.ibb.co/pzyGrWx/Windowy-Gif.gif"></img></a><br>
</p>
<p align="center">
  <img alt= "Last Commit" src= "https://img.shields.io/github/last-commit/DayKoala/Windowy?color=green">
  <img alt= "Downloads" src= "https://img.shields.io/github/downloads/DayKoala/Windowy/latest/total">
</p>

# About

- **[Windowy](https://github.com/DayKoala/Windowy)** is a transaction-focused temporary inventory generator made for
**[PocketMine-MP](https://github.com/pmmp/PocketMine-MP)**.

# How to use

- Windowy comes with 3 registered inventories

```php

use DayKoala\inventory\WindowFactory;

$id = WindowFactory::CHEST;
$id = WindowFactory::DOUBLE_CHEST;
$id = WindowFactory::FURNACE;

```

- You can get the desired inventory using:

```php

$window = WindowFactory::getInstance()->get($player, $id, $name);

```

- If you want to simplify:

```php

use DayKoala\Windowy;

$window = Windowy::getWindow($player, $id, $name);

```

# Registering inventory

- If you want to register an inventory, use:

```php

$window = new MyWindow(WindowTypes::CONTAINER, 27, Tile:class, BlockLegacyIds::Block);

```

- You can also set a fixed transaction for inventory, before or after registering, using:

```php

use DayKoala\inventory\action\WindowTransaction;

$transaction = function(WindowTransaction $action) : Bool{
   $player = $action->getPlayer();
   $player->sendMessage("I won't let you take this item haha!");
   return false;
};

$window->setTransaction($transaction);

```
