<?php

namespace App\Form;

use App\Entity\Location;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AllTripSearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('site', EntityType::class, array(
                'label'=>'Site : ',
                'placeholder'=>'Selectionnez un site',
                'required'=>false,
                'mapped'=>false,
                'class'=>Location::class,
                'choice_label'=>'name'))
            ->add('name', TextType::class, array('label'=>'Le nom de la sortie contient : ','required'=>false))
//            ->add('Organisateur', CheckboxType::class, array("label"=>"Sortie dont je suis l'oganisateur/trice",'required'=>false))
//            ->add('Inscrit', CheckboxType::class, array("label"=>"Sortie auxquelles je suis inscrit/e",'required'=>false))
//            ->add('PasInscrit', CheckboxType::class, array("label"=>"Sortie auxquelles je ne suis pas inscrit/e",'required'=>false))
//            ->add('PastTrip', CheckboxType::class, array("label"=>"Sorties passées",'required'=>false))
//            ->add('submit', SubmitType::class,array("label"=>'Rechercher'))


        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
