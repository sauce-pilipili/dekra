<?php

namespace App\Form;

use App\Entity\Reference;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReferenceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('idKizeoForm',TextType::class,[
                'label'=>false,
                'required'=>false
            ])
            ->add('dateRestitution',DateType::class,[
                'label'=>false,
                'widget' => 'single_text',
                'required'=>false
            ])
            ->add('validation',CheckboxType::class,[
                'label'=>false,
                'required'=>false
            ])
            ->add('complet',CheckboxType::class,[
                'label'=>false,
                'required'=>false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Reference::class,
        ]);
    }
}
