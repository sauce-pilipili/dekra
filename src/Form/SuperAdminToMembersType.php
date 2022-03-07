<?php

namespace App\Form;

use App\Entity\Region;
use App\Entity\User;
use ContainerFyJMW4c\getRolesRepositoryService;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SuperAdminToMembersType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email',EmailType::class,[
                'label' =>false
            ])
            ->add('roles', ChoiceType::class, ['choices' =>
                [   'niveau d\'accès '=> "change",
                    'Call Center' => 'ROLE_CALL_CENTER',
                    'Client' => 'ROLE_CLIENT',
                    'Contrôleur' => 'ROLE_CONTROLLER',
                    'Administrateur' => 'ROLE_ADMIN',
                    'Super Administrateur' => 'ROLE_SUPER_ADMIN',
                ],
                'mapped'=>false,
                'label'=> false,
                'required'  => true,
                'multiple' => false,
                'empty_data' => 'ok'
            ])
            ->add('name',TextType::class,[
                'label'=>false
            ])
            ->add('region',EntityType::class, [
                'class' => Region::class,
                'label'=>false,
                'choice_label' => 'Name',
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
