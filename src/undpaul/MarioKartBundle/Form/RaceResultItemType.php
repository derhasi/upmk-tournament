<?php

namespace undpaul\MarioKartBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class RaceResultItemType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('pos_abs', 'integer', array('attr' => array('size' => 3)))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'undpaul\MarioKartBundle\Entity\RaceResultItem'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'undpaul_mariokartbundle_raceresultitem';
    }
}
