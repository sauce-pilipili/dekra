<?php

namespace App\Form;

use App\Entity\Departements;
use App\Entity\Specialite;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DataType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name',EntityType::class, [
                'class' => Departements::class,
                'label'=>false,
                'required'=>false,
                'choice_label' => 'Name',
                'placeholder'=>'département',
                'empty_data'=>'',
            ])
            ->add('specialite',EntityType::class, [
                'class' => Specialite::class,
                'label'=>false,
                'required'=>false,
                'choice_label' => 'Name',
                'placeholder'=>'spécialitées',
                'empty_data'=>'',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
