<?php

namespace App\Form;

use App\Entity\Campus;
use App\Entity\Place;
use App\Entity\Status;
use App\Entity\Travel;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Range;

class TravelCancelType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => false,
                'attr' => [
                    'hidden' => true,

                ]
            ])
            ->add('dateStart', DateTimeType::class, [
                'label' => false,
                'widget' => 'single_text',
                'attr' => [
                    'hidden' => true,

                ]
            ])
            ->add('duration', TimeType::class, [
                'label' => false,
                'widget' => 'single_text',
                'attr' => [
                    'hidden' => true,

                ]

            ])
            ->add('limitDateSubscription', DateType::class, [
                'label' => false,
                'widget' => 'single_text',
                'attr' => [
                    'hidden' => true,

                ]
            ])
            ->add('nbMaxTraveler', ChoiceType::class, [
                'label' => false,
                'attr' => ['hidden' => true],
                'choices' => array_combine(range(0, 50), range(0, 50)),
                'constraints' => [
                    new Range([
                        'min' => 0,
                        'max' => 50,
                    ])
                ]
            ])
            ->add('infos', TextareaType::class, [
                'label' => false,
                'attr' => [
                    'hidden' => true,

                ]

            ])
            ->add('status', EntityType::class, [
                'class' => Status::class,
                'choice_label' => 'wording',
                'label' => false,
                'attr' => [
                    'hidden' => true,

                ]

            ])
            ->add('campusOrganiser', EntityType::class, [
                'disabled' => true,
                'class' => Campus::class,
                'choice_label' => 'name',
                'label' => false,
                'attr' => [
                    'hidden' => true,

                ]
            ])
            ->add('place', EntityType::class, [
                'disabled' => true,
                'class' => Place::class,
                'choice_label' => 'name',
                'label' => false,
                'attr' => [
                    'hidden' => true,

                ]

            ])->add('cancelMessage', TextareaType::class, [
                'label' => 'Cancel motif'


            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Travel::class,
        ]);
    }
}
