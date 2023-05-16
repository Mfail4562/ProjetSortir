<?php

namespace App\Form;

use App\Entity\Campus;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('Pseudo', TextType::class,[
                'label' => 'Pseudo : ',
                'attr' => [
                    'placeholder' => 'Your nickname'
                ]
            ])
            ->add('firstname', TextType::class,[
                'label' => 'First name : ',
                'attr' => [
                    'placeholder' => 'Jean'
                ]
            ])
            ->add('lastname', TextType::class,[
                'label' => 'Last name : ',
                'attr' => [
                  'placeholder' => 'Dupond'
                ]
            ])
            ->add('phoneNumber', TextType::class,[
                'label' => 'Phone Number : ',
                'attr' => [
                    'placeholder' => 'start by +33 / 0033 / 06 / 07'
                ]
            ])
            ->add('email', EmailType::class,[
                'label' => 'Email : ',
                'attr' =>[
                    'placeholder' => 'exemple@exemple.com'
                ]
            ])
            ->add('plainPassword', PasswordType::class, [
                'label' => 'Password : ',
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => false,
                'attr' => [
                    'autocomplete' => 'new-password',
                    'placeholder' => '8 characters min',
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a password',
                    ]),
                    new Length([
                        'min' => 8,
                        'minMessage' => 'Your password should be at least {{ limit }} characters',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],
            ])
            ->add('userCampus', EntityType::class,[
                'class' => Campus::class,
                'choice_label' => 'name',
                'label' => 'Campus : ',

            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
