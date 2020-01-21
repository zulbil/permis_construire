<?php

namespace App\Form;

use App\Entity\PersonneMorale;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ResetType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PersonneMoraleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('raison_social', TextType::class, [
                'required' => true
            ])
            ->add('sigle')
            ->add('numero_id_nationale')
            ->add('numero_registre_commerce')
            ->add('email')
            ->add('telephone')
            ->add('forme_juridique')
            ->add('secteur_activite')
            ->add('activite')
            ->add('etat')
            ->add('NIF')
            ->add('adresse')
            ->add('adresses_succursales')
            ->add('save', SubmitType::class, [
                'label' => 'Enregistrer les informations',
                'attr' => ['class' => 'btn btn-brand btn-bold']
            ])
            ->add('reset', ResetType::class, [
                'label' => 'Annuler',
                'attr'  => ['class' => 'btn btn-secondary ml-4']
            ])
            //->add('utilisateur')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => PersonneMorale::class,
        ]);
    }
}
