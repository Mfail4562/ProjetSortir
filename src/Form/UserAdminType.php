<?php

    namespace App\Form;

    use App\Entity\Campus;
    use App\Entity\User;
    use Symfony\Bridge\Doctrine\Form\Type\EntityType;
    use Symfony\Component\Form\AbstractType;
    use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
    use Symfony\Component\Form\Extension\Core\Type\FileType;
    use Symfony\Component\Form\FormBuilderInterface;
    use Symfony\Component\OptionsResolver\OptionsResolver;

    class UserAdminType extends AbstractType
    {
        public function buildForm(FormBuilderInterface $builder, array $options): void
        {
            $builder
                ->add('email')
//           ->add('roles')
//          ->add('password')
                ->add('pseudo')
                ->add('lastname')
                ->add('firstname')
                ->add('phoneNumber')
                //->add('admin')
                ->add('actif')
                ->add(
                    'roles', ChoiceType::class, [
                        'choices' => ['Super Admin' => 'ROLE_SUPER_ADMIN', 'Admin' => 'ROLE_ADMIN', 'Organizer' => 'ROLE_ORGANISER', 'Participant' => 'ROLE_PARTICIPANT', 'User' => 'ROLE_USER'],
                        'expanded' => true,
                        'multiple' => true,
                    ]
                )
                ->add('userCampus', EntityType::class, [
                        'class' => Campus::class,
                        'choice_label' => 'name'
                    ]
                )->add('avatar', FileType::class, [
                    'mapped' => false,
                    'label' => 'if you want to have some avatar :',
                    'required' => false
                ]);
        }

        public function configureOptions(OptionsResolver $resolver): void
        {
            $resolver->setDefaults([
                'data_class' => User::class,
            ]);
        }
    }
