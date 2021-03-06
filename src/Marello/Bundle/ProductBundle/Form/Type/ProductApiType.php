<?php

namespace Marello\Bundle\ProductBundle\Form\Type;

use Marello\Bundle\InventoryBundle\Form\Type\InventoryItemApiType;
use Marello\Bundle\InventoryBundle\Form\DataTransformer\InventoryItemUpdateApiTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotNull;

class ProductApiType extends AbstractType
{
    const NAME = 'marello_product_api_form';

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('sku')
            ->add('status', 'entity', [
                'class' => 'Marello\Bundle\ProductBundle\Entity\ProductStatus',
            ])
            ->add(
                'weight',
                'number',
                [
                    'required' => false,
                    'scale' => 2,
                ]
            )
            ->add('prices', 'marello_product_price_collection')
            ->add('channels')
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class'         => 'Marello\Bundle\ProductBundle\Entity\Product',
                'cascade_validation' => true,
                'csrf_protection'    => false,
            ]
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return self::NAME;
    }
}
