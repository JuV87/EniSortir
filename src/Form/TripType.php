<?php

namespace App\Form;

use App\Entity\City;
use App\Entity\Place;
use App\Entity\Trip;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
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
            ->add('city', EntityType::class, ['class'=> 'App\Entity\City',
                                                    'choice_label'=>'name',
                                                     'label'=> 'Ville : ',
                                                     'mapped' => false,
                                                    'placeholder' =>'Sélectionner une ville : '
            ])
        ;

        $builder->get('city')->addEventListener(
            FormEvents::POST_SUBMIT,
            function (FormEvent $event){
                $form = $event->getForm();
                $this->addLieuField($form->getParent(), $form->getData());
            }
        );

        $builder->addEventListener(
            FormEvents::POST_SET_DATA,
            function (FormEvent $event){
                $data = $event->getData();
                /* @var $lieu \App\Entity\Place */
                $lieu = $data->getPlace();

                $form = $event->getForm();
                if($lieu){
                    $ville = $lieu->getCity();
                    $this->addLieuField($form, $ville);
                    $form->get('city')->setData($ville);
                }else{
                    $this->addLieuField($form, null);
                }

            }
        );
    }


private function addLieuField(FormInterface $form, ?City $city){
    $builder = $form->add('place', EntityType::class,[
        'class' => Place::class,
        'label'=> 'Lieu :',
        'choice_label' => 'name',
        'placeholder' => $city ? 'Sélectionnez votre lieu' : 'Sélectionnez une ville',
        'required' => true,
        'auto_initialize' => false,
        'choices' => $city ? $city->getPlaces() : []
    ]);
}



    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Trip::class,
        ]);
    }
}
