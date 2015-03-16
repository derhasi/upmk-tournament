<?php

namespace undpaul\MarioKartBundle\Listener;

use Doctrine\ORM\Event\PreFlushEventArgs;
use undpaul\MarioKartBundle\Entity\RaceResultItem;
use undpaul\MarioKartBundle\Service\PointRules;

/**
 * Event listener to handle RaceResultItem updates.
 */
class ResultItemListener {

    /**
     * @var \undpaul\MarioKartBundle\Service\PointRules
     */
    protected $pointRules;

    public function __construct(PointRules $rules)
    {
        $this->pointRules = $rules;
    }

    /**
     * Recalculates race result items on preFlush.
     *
     * We cannot use preUpdate(), as changeing a single result item may take
     * effect on the points of others of the same race.
     *
     * @param \Doctrine\ORM\Event\PreFlushEventArgs $args
     */
    public function preFlush(PreFlushEventArgs $args)
    {

        $em = $args->getEntityManager();
        $map = $em->getUnitOfWork()->getIdentityMap();

        // We need have to recalculate all loaded race results, as a result item
        // may have changed.
        if (!empty($map['undpaul\MarioKartBundle\Entity\RaceResultItem'])) {

            foreach ($map['undpaul\MarioKartBundle\Entity\RaceResultItem'] as $entity) {
                if ($entity instanceof RaceResultItem) {
                    $entity->calculate($this->pointRules);
                }
            }
        }
    }

}