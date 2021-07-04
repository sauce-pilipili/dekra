<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserEditType extends AbstractType
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
                'invalid_message' => 'les mots de passe doivent être similaires',
                'options' => ['attr' => ['class' => 'password-field', 'label'=>false]],
                'required' => true,
                'first_options'  => ['label' => false],
                'second_options' => ['label' => false],
            ])
            ->add('name',TextType::class,[
                'label'=>false
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
