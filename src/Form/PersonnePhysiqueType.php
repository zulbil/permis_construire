<?php

namespace App\Form;

use App\Entity\Fonction;
use App\Entity\PersonnePhysique;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\ResetType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PersonnePhysiqueType extends AbstractType
{
    private $fonctions;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->fonctions    =   $entityManager->getRepository(Fonction::class)->findAll();
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom', TextType::class, ['label' => 'Nom'])
            ->add('postnom', TextType::class, ['label' => 'Postnom'])
            ->add('prenom', TextType::class, ['label' => 'Prénom'])
            ->add('sexe', ChoiceType::class, [
                'label'     =>  'Sexe',
                'choices'   => [
                    'Masculin' => 'M',
                    'Féminin' => 'F'
                ]
            ])
            ->add('etat_civil', ChoiceType::class, [
                'label'     => 'Etat Civil',
                'choices'   => [
                    'Célibataire'   => 'célibataire',
                    'Marié(e)'      => 'marié(e)',
                    'Divorcé(e)'    => 'divorcé(e)',
                    'Veuf(ve)'      => 'veuf(ve)'
                ]
            ])
            ->add('lieu_de_naissance', TextType::class, ['label' => 'Lieu de Naissance'])
            ->add('date_de_naissance', TextType::class, ['label' => 'Date de Naissance', 'mapped' => false ])
            ->add('nationalite', TextType::class, ['label' => 'Nationalité'])
            ->add('email', EmailType::class, ['label' => 'Email'])
            ->add('telephone', NumberType::class, ['label' => 'Numero de Telephone'])
            ->add('profession', TextType::class, ['label' => 'Profession'])
            ->add('fonction', EntityType::class, [
                'label'         => 'Fonction',
                'class'         => Fonction::class,
                'choice_label'  => 'nom'
            ])
            ->add('NIF', TextType::class, ['label' => 'NIF'])
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
            'data_class' => PersonnePhysique::class,
        ]);
    }

    public function getAllFonctions(EntityManagerInterface $entityManager) {
        $fonctions = $entityManager->getRepository(Fonction::class)->findAll();
        return $fonctions;
    }
}
