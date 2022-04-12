<?php

namespace App\Form;

use App\Entity\Trip;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TripType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', null,['label'=>'Nom : ',])
            ->add('datestart', null,['label'=>'Date et heure de la sortie : ',])
            ->add('duration', null,['label'=>'Durée (en minutes) : ',])
            ->add('dateend', null,['label'=>'Date limite d\'inscription : ',])
            ->add('nbsubscriptionmax', null,['label'=>'Nombre de places : ',])
            ->add('descriptioninfos', null,['label'=>'Description et infos : ',])
            ->add('statustrip', null,['label'=>'Statut de la sortie : ',])
            ->add('urlpicture', null,['label'=>'Ajouter une photo : ',])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Trip::class,
        ]);
    }
}
