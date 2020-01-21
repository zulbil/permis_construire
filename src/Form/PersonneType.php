<?php

namespace App\Form;

use App\Entity\Activite;
use App\Entity\Fonction;
use App\Entity\Personne;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType; 
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class PersonneType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom', TextType::class, [
                'required'  => true,
                'label'     => 'Nom/Raison social',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Ce champ est obligatoire',
                    ])
                ]
            ])
            ->add('postnom', TextType::class, [
                'label' =>  'Postnom/Sigle', 
                'required'  => true, 
                'constraints' => [
                    new NotBlank([
                        'message' => 'Ce champ est obligatoire'
                    ])
                ]
            ])
            ->add('prenom', TextType::class, [
                'label' =>  'Prénom/Autres'
            ])
            ->add('sexe', ChoiceType::class, [
                'label'     =>  'Sexe',
                'choices'   => [
                    'Choisissez votre sexe' => '',
                    'Masculin'  => 'M',
                    'Féminin'   =>  'F'
                ]
            ])
            ->add('adresse', TextareaType::class, [
                'label' => 'Adresse/Siège Social',
                'required'  => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Ce champ est obligatoire',
                    ])
                ]
            ])
            ->add('fk_entite', HiddenType::class, ['mapped' => false ])
            ->add('numero', HiddenType::class, ['mapped' => false ])
            ->add('etat_civil',  ChoiceType::class, [
                'label'     => 'Etat Civil',
                'choices'   => [
                    'Choisissez votre état civil' => '',
                    'Célibataire'   => 'célibataire',
                    'Marié(e)'      => 'marié(e)',
                    'Divorcé(e)'    => 'divorcé(e)',
                    'Veuf(ve)'      => 'veuf(ve)'
                ]
            ])
            ->add('lieu_de_naissance', TextType::class, ['label' => 'Lieu de Naissance'])
            ->add('date_de_naissance', TextType::class, ['label' => 'Date de Naissance' , 'mapped' => false ])
            ->add('nationalite', CountryType::class, ['label' => 'Nationalité'])
            ->add('email', EmailType::class, ['label' => 'E-Mail'])
            ->add('telephone', TelType::class, ['label' => 'Téléphone'] )
            ->add('nif', TextType::class, [
                'label' => 'NIF',
                'required'  => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez fournir votre numéro d\'identification fiscale'
                    ])
                ]
            ])
            ->add('secteur_activites', EntityType::class, [
                'label'         =>  'Secteur d\'activité',
                'class'         =>  Activite::class,
                'choice_label'  => 'nom'
            ])
            ->add('etat', HiddenType::class, ['attr'  =>  ['value' => 1 ] ])
            ->add('numero_id_nationale', TextType::class, ['label' =>  'Numéro d\'identification'])
            ->add('numero_registre_commerce', TextType::class, ['label' =>  'Numéro de registre de commerce'])
            ->add('forme_juridique', ChoiceType::class, [
                'label' => 'Forme Juridique',
                'choices'   => [
                    'Choisissez le type de personne' => '',
                    'Personne Physique'    => 'physique',
                    'Personne Morale'      => 'morale'
                ], 
                'required'  => true, 
                'constraints'   => [
                    new NotBlank([
                        'message' => 'Veuillez choisir le type de la personne'
                    ])
                ]
            ])
            ->add('profession', EntityType::class, [
                'label'         => 'Profession',
                'class'         => Fonction::class,
                'choice_label'  => 'nom'
            ])
            ->add('save', SubmitType::class, [
                'label'     => 'Enregistrer les informations',
                'attr'      =>  ['class' => 'btn-brand btn-bold btn-submit-person']
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Personne::class,
        ]);
    }
}
