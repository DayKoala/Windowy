<p align="center">
  <a href="https://github.com/DayKoala/Windowy/stargazers"><img src="https://i.ibb.co/pzyGrWx/Windowy-Gif.gif"></img></a><br>
</p>
<p align="center">
  <img alt= "Last Commit" src= "https://img.shields.io/github/last-commit/DayKoala/Windowy?color=green">
</p>

# About

- **[Windowy](https://github.com/DayKoala/Windowy)** is a temporary inventory generator and manager, focused on item transactions, for
**[PocketMine-MP](https://github.com/pmmp/PocketMine-MP)**.

- Functional as a Plugin and Library, for multiple different tasks.

# WindowLibrary

- WindowLibrary was created for direct tasks in plugins, follow the example of how to use below:

```php

# Do the following function in your PluginBase to activate it (WindowyLibrary).

if(Windowy::hasHolder() === false){
   Windowy::setHolder($this);
}


```

# Getting a Window

- Windowy comes with 4 registered inventories

```php

use DayKoala\inventory\WindowIds;

$id = WindowIds::CHEST;
$id = WindowIds::DOUBLE_CHEST;
$id = WindowIds::FURNACE;
$id = WindowIds::HOPPER;

```

- You can get the desired inventory using:

```php

use DayKoala\inventory\WindowFactory;

/**
 *
 * @param String $id
 * @param String|null $name
 * @return SimpleWindow|null
 * 
 */

$window = WindowFactory::getInstance()->get($id, $name);

```

- If you want to simplify:

```php

use DayKoala\Windowy;

$window = Windowy::getWindow($id, $name);

```

- It is ``not mandatory`` for you to fill in the ``name`` of the inventory, if you do not fill it or leave it in ``null``, the result will be the name of the inventory selected by the ``id``.

# Registering your Window

- You need the inventory you are going to register to be a ``SimpleWindow extension``, otherwise it won't work. Register ``your inventory`` like this:
 
```php

use DayKoala\block\BlockEntityMetadata;

use pocketmine\network\mcpe\protocol\types\inventory\WindowTypes;

use pocketmine\block\tile\Tile;
use pocketmine\block\BlockLegacyIds;

/**
 *
 * @param Int $network
 * @param Int $size
 * @param BlockEntityMetadata $metadata
 * 
 */

$window = new MyWindow(WindowTypes::CONTAINER, 27, new BlockEntityMetadata(Tile::class, BlockLegacyIds::Block));

```

```php

/**
 *
 * @param String $id
 * @param SimpleWindow $inventory
 * @param boolean $override
 * 
 */

WindowFactory::register('MyWindow', $window, $override);

```

# Adding Actions to Your Window

- Actions can be ``added before or after`` registration, as well as items and derivatives. You can add ``a specific action`` to your inventory using:

```php

use DayKoala\inventory\action\WindowTransaction;

$callback = function(WindowTransaction $action){
   $player = $action->getPlayer();
   $player->sendMessage("I won't let you take this item haha!");
   $action->cancel();
};

/**
 *
 * @param Closure $callback
 * @return self
 * 
 */

$window->setTransaction($callback);

```

- If you want ``a certain item`` to have some ``action`` in the inventory, you can use:

```php

$window->setItem($slot, $item, $callback);

```

or

```php

/**
 *
 * @param Item $item
 * @param Closure $callback
 * @return self
 * 
 */

$window->setItemCallback($item, $callback);

```

- If the ``transaction is not canceled`` and the item moved from its defined slot, the ``item's action will be removed``.

# Closing your Window Differently

- Usually we need to do things in a different way and ``closing a window`` with a different action can help you, so you can ``add an action`` when closing it using:

```php

use DayKoala\inventory\action\WindowAction;

$callback = function(WindowAction $action){
   $player = $action->getPlayer();
   $player->sendMessage("Closing...");
};

/**
 *
 * @param Closure $callback
 * @return self
 * 
 */

$window->setCloseCallback($callback);

```
