<?php
/**
 * Created by PhpStorm.
 * User: derhasi
 * Date: 12.02.15
 * Time: 09:32
 */

namespace derhasi\upmkTournament;


abstract class CollectionBase extends \ArrayObject {

  /**
   * Magic method to make array functions work with the object.
   *
   * Example: $this->array_keys()
   */
  public function __call($func, $argv) {
    if (!is_callable($func) || substr($func, 0, 6) !== 'array_') {
      throw new \BadMethodCallException(__CLASS__ . '->' . $func);
    }
    return call_user_func_array($func,
      array_merge(array($this->getArrayCopy()), $argv));
  }

  /**
   * @param \derhasi\upmkTournament\ItemInterface $item
   */
  public function add(ItemInterface $item) {
    if (!$this->hasItem($item)) {
      $this->offsetSet($item->getID(), $item);
    }
  }

  /**
   * @param ItemInterface[] $items
   */
  public function addMultiple(array $items) {
    foreach ($items as $item) {
      $this->add($item);
    }
  }

  /**
   * @param \derhasi\upmkTournament\ItemInterface $item
   * @return bool
   */
  public function hasItem(ItemInterface $item) {
    return $this->offsetExists($item->getID());
  }

  /**
   * Retrieve the original singleton instance for the given item.
   *
   * @param \derhasi\upmkTournament\ItemInterface $item Original or copy of the initial item.
   *
   * @return \derhasi\upmkTournament\ItemInterface
   */
  public function getItem(ItemInterface $item) {
    return $this->offsetGet($item->getID());
  }


}