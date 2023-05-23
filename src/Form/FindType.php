<?php

namespace App\Form;

use App\Data\FindData;
use App\Entity\Campus;
use App\Entity\Search;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FindType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('travelByName', TextType::class, [
                'label'=>'Le nom de la sortie contient :',
                'attr'=>[
                    'class'=>'form-control',
                ],
                'required' => false
            ])
            ->add('campusToSearchTravel', EntityType::class, [
                'class'=>Campus::class,
                'label'=>'Choix du campus:',
                'choice_label'=>'name',
                'placeholder'=> '--choose any campus--',
                'required'=>false,
            ])
            ->add('leaderTravel', CheckboxType::class, [
                'label'=>'Sorties dont je suis l\'organisateur',
                'required' => false,
            ])
            ->add('statusId', CheckboxType::class, [
                'label' => 'Sortie passées',
                'required' => false,
            ])

            ->add('travelsSubscripted', CheckboxType::class, [
                'label' => 'Sortie au quel je suis inscrit/e',
                'required' => false,

            ])
            ->add('travelsNotSubscripted', CheckboxType::class, [
                'label' => 'Sortie au quel je ne suis pas inscrit/e',
                'required' => false,

            ]);


    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => FindData::class,
            'method' => 'GET',
            'csrf_protection' => false
        ]);
    }
    public function getBlockPrefix(): string
    {
        return '';
    }

}