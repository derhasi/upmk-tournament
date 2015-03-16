<?php

namespace undpaul\MarioKartBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class RaceType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
          ->add('results', 'collection', array(
            'type' => new RaceResultItemType(),
            'label' => false,
          ))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'undpaul\MarioKartBundle\Entity\Race'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'undpaul_mariokartbundle_race';
    }
}
