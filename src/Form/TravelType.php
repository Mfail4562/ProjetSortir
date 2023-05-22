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

    class TravelType extends AbstractType
    {
        public function buildForm(FormBuilderInterface $builder, array $options): void
        {
            $builder
                ->add('name', TextType::class, [
                ])
                ->add('dateStart', DateTimeType::class, [
                    'widget' => 'single_text',
                    'input_format' => 'd-m-Y H:m',
                    'attr' => [
                        'class' => 'form-control datetimepicker',

                    ]
                ])
                ->add('duration', TimeType::class, [
                    'label' => 'Duration',
                    'widget' => 'choice',
                ])
                ->add('limitDateSubscription', DateType::class, [
                    'widget' => 'single_text'])
                ->add('nbMaxTraveler', ChoiceType::class, [
                    'label' => 'Maximum number of travelers',
                    'choices' => array_combine(range(0, 50), range(0, 50)),
                    'constraints' => [
                        new Range([
                            'min' => 0,
                            'max' => 50,
                        ])
                    ]
                ])
                ->add('infos', TextareaType::class, [
                    'label' => 'Description'
                ])
                ->add('status', EntityType::class, [
                    'class' => Status::class,
                    'choice_label' => 'wording',
                    'placeholder' => '--Choice any status--',

                ])
                ->add('campusOrganiser', EntityType::class, [
                    'class' => Campus::class,
                    'choice_label' => 'name'
                ])
                ->add('place', EntityType::class, [
                    'class' => Place::class,
                    'choice_label' => 'name'

                ]);
        }

        public function configureOptions(OptionsResolver $resolver): void
        {
            $resolver->setDefaults([
                'data_class' => Travel::class,
            ]);
        }
    }
