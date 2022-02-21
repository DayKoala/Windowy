<p align="center">
  <a href="https://github.com/DayKoala/Windowy/stargazers"><img src="https://i.ibb.co/pzyGrWx/Windowy-Gif.gif"></img></a><br>
</p>
<p align="center">
  <img alt= "Last Commit" src= "https://img.shields.io/github/last-commit/DayKoala/Windowy?color=green">
</p>

# About

- **[Windowy](https://github.com/DayKoala/Windowy)** is a temporary inventory generator and manager, focused on item transactions, for
**[PocketMine-MP](https://github.com/pmmp/PocketMine-MP)**.

- Functional as a plugin, for multiple different tasks.

# Getting a Window

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

- It is ``not mandatory`` for you to fill in the ``name`` of the inventory, if you do not fill it or leave it in ``null``, the result will be the name of the inventory selected by the ``id``.

# Registering your Window

- You need the inventory you are going to register to be a ``Window extension``, otherwise it won't work. Register ``your inventory`` like this:
 
```php

use DayKoala\block\BlockEntityMetadata;

$window = new MyWindow(WindowTypes::CONTAINER, 27, new BlockEntityMetadata(Tile::class, BlockLegacyIds::Block));

```

```php

WindowFactory::register('MyWindow', $window);

```

# Adding Actions to Your Window

- Actions can be ``added before or after`` registration, as well as items and derivatives. You can add ``a specific action`` to your inventory using:

```php

use DayKoala\inventory\action\WindowTransaction;

$transaction = function(WindowTransaction $action){
   $player = $action->getPlayer();
   $player->sendMessage("I won't let you take this item haha!");
   $action->cancel();
};

$window->setTransaction($transaction);

```

- If you want ``a certain item`` to have some ``action`` in the inventory, you can use:

```php

$window->setItem($slot, $item, $transaction);

```

or

```php

$window->setItemTransaction($item, $transaction);

```

- If the ``transaction is not canceled`` and the item moved from its defined slot, the ``item's action will be removed``.
