<?php

namespace App\Form;

use App\Entity\Controleur;

use App\Entity\Departements;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ControleurType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom',TextType::class,[
                'label'=>false
            ])
            ->add('prenom',TextType::class,[
                'label'=>false
            ])
            ->add('email',EmailType::class,[
                'label'=>false
            ])
            ->add('telnumber', TelType::class,[
                'label'=>false
            ])
            ->add('kizeoId', TextType::class,[
                'label'=>false
            ])
            ->add('departement',null,[
                'label'=>false
            ])
            ->add('specialite',null,[
        'label'=>false
    ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Controleur::class,
        ]);
    }
}
