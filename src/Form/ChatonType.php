<?php

namespace App\Form;

use App\Entity\Categorie;
use App\Entity\Chaton;
use App\Entity\Proprietaires;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ChatonType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('Nom')
            ->add('Sterelise')
            ->add('Photo')
            ->add('Categorie', EntityType::class, [
                'class' => Categorie::class, //choix de la classe lié
                'choice_label' => "titre", //choix de ce qui sera affiché comme texte
                'multiple' => false,
                'expanded' => false
            ])
            ->add('Proprietaires', EntityType::class,[
                'class' => Proprietaires::class,
                'choice_label' => "nom",
                'multiple' => true,
                'expanded' => false
            ])
            ->add('OK', SubmitType::class, ["label"=>"OK"])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Chaton::class,
        ]);
    }
}
