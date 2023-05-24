<?php

namespace App\Form;

use App\Entity\City;
use App\Entity\Place;
use App\Entity\Status;
use App\Entity\Travel;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Range;

class TravelType extends AbstractType
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

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
            ->add('place', EntityType::class, [
                'class' => Place::class,
                'choice_label' => 'name',
                'placeholder' => 'Choose a place',
                'required' => false,

            ]);


        $builder->addEventListener(FormEvents::PRE_SET_DATA, array($this, 'onPreSetData'));
        $builder->addEventListener(FormEvents::PRE_SUBMIT, array($this, 'onPreSubmit'));

    }


    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Travel::class,
        ]);
    }

//
    function onPreSubmit(FormEvent $event): void
    {
        $form = $event->getForm();
        $data = $event->getData();
        $city = $this->entityManager->getRepository(City::class)->find($data['city']);
        $this->addElements($form, $city);

    }

    protected function addElements(FormInterface $form, City $city = null): void
    {
        $form->add('city', EntityType::class, array(
            'required' => true,
            'data' => $city,
            'placeholder' => 'Select a City...',
            'class' => City::class,
            'choice_label' => 'name',
            'mapped' => false));

        $places = array();

//        if ($city) {
//            $repoPlace = $this->entityManager->getRepository(Place::class);
//            $places = $repoPlace->createQueryBuilder('p')
//                ->where("p.city= city")
//                ->setParameter('city', $city)
//                ->getQuery()
//                ->getResult();
//
//        }

    }

    function onPreSetData(FormEvent $event): void
    {
        $form = $event->getForm();
        $travel = $event->getData();

        $city = $travel->getPlace() ? $travel->getPlace() : null;
        $this->addElements($form, $city);
    }

    public function getBlockPrefix(): string
    {
        return 'AppEntity_Place';
    }

}
