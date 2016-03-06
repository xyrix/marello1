<?php

namespace Marello\Bundle\PricingBundle\Form\EventListener;

use Doctrine\ORM\EntityManager;

use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

use Marello\Bundle\PricingBundle\Model\PricingAwareInterface;
use Marello\Bundle\SalesBundle\Model\SalesChannelAwareInterface;
use Marello\Bundle\PricingBundle\Entity\ProductPrice;

class DefaultPricingSubscriber implements EventSubscriberInterface
{
    /** @var EntityManager $em */
    protected $em;

    /**
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    /**
     * Get subscribed events
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            FormEvents::PRE_SET_DATA    => 'addPricingData',
        ];
    }

    /**
     * Preset data for pricing
     * @param FormEvent $event
     */
    public function addPricingData(FormEvent $event)
    {
        $entity = $event->getData();
        $form   = $event->getForm();

        if (!$entity || null === $entity->getId()) {
            if ($entity instanceof PricingAwareInterface && $entity instanceof SalesChannelAwareInterface) {
                foreach ($entity->getChannels() as $channel) {
                    $price = new ProductPrice();
                    $price->setCurrency($channel->getCurrency());
                    $price->setValue(0);
                    $entity->addPrice($price);
                }
                $event->setData($entity);
            }
        }

        $form->add(
            'prices',
            'marello_product_price_collection'
        );
    }
}