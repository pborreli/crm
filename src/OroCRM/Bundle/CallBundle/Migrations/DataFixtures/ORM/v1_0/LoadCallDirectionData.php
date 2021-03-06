<?php

namespace OroCRM\Bundle\CallBundle\Migrations\DataFixtures\ORM\v1_0;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;

use OroCRM\Bundle\CallBundle\Entity\CallDirection;

class LoadCallDirectionData extends AbstractFixture
{
    /**
     * @var array
     */
    protected $data = array(
        'incoming' => 'Incoming',
        'outgoing' => 'Outgoing',
    );

    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        foreach ($this->data as $name => $label) {
            $callDirection = new CallDirection($name);
            $callDirection->setLabel($label);
            $manager->persist($callDirection);
        }

        $manager->flush();
    }
}
