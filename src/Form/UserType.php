<?php

namespace App\Form;

use App\Entity\Region;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email',EmailType::class,[
            'label' =>false
            ])
            ->add('password', RepeatedType::class, [
                'label'=>false,
                'type' => PasswordType::class,
                'invalid_message' => 'The password fields must match.',
                'options' => ['attr' => ['class' => 'password-field', 'label'=>false]],
                'required' => true,
                'first_options'  => ['label' => false],
                'second_options' => ['label' => false],
            ])
            ->add('name',TextType::class,[
                'label'=>false
            ])
            ->add('region',EntityType::class, [
                'class' => Region::class,
                'label'=>false,
                'choice_label' => 'Name',
            ])
            ->add('roles', ChoiceType::class, ['choices' =>
                [
                    'Client' => 'ROLE_CLIENT',
                    'Call Center' => 'ROLE_CALL_CENTER',
                    'Contrôleur' => 'ROLE_CONTROLLER',
                    'Administrateur' => 'ROLE_ADMIN',
                    'Super Administrateur' => 'ROLE_SUPER_ADMIN',
                ],
                'mapped'=>false,
                'label'=> false,
                'required'  => true,
                'multiple' => false
            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
