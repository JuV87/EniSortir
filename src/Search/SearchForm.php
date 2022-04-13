<?php

namespace App\Search;

use App\Entity\Location;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchForm extends AbstractType
{


    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class'=>SearchTrip::class,
            'method'=>'GET',
        ]);
    }

    public function getBlockPrefix()
    {
        return '';
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label'=>"Le nom de la sortie contient:",
                'required' =>false,
                'attr'=>[
                    'placeholder'=> 'search'
                ]
            ])
            ->add('location',EntityType::class, [
                'label'=> 'Site',
                'required'=>false,
                'class'=>Location::class

            ])
        ;
    }
}