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
   * @param \derhasi\upmkTournament\ItemInterface $item
   */
  public function add(ItemInterface $item) {
    if (!$this->hasItem($item)) {
      $this->offsetSet($item->getID(), $item);
    }
  }

  /**
   * @param \Traversable $items
   */
  public function addMultiple(\Traversable $items) {
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

  /**
   * @return string[]
   */
  public function getIDs() {
    return array_keys($this->getArrayCopy());

  }


}