<?php

namespace App\Form;

use App\Entity\Campus;
use App\Entity\Place;
use App\Entity\Status;
use App\Entity\Travel;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TravelType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('dateStart')
            ->add('duration')
            ->add('limitDateSubscription')
            ->add('nbMaxTraveler')
            ->add('infos')
            //     ->add('leader')
            // ->add('subscriptionedTravelers')
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
