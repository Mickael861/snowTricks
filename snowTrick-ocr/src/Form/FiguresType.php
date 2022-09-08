<?php

namespace App\Form;

use App\Entity\Figures;
use App\Entity\FiguresGroups;
use App\Form\FiguresImagesType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class FiguresType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom de la figure',
                'required' => true
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Déscription',
                'required' => true
            ])
            ->add('figure_group', EntityType::class, [
                'label' => 'Catégories',
                'class' => FiguresGroups::class,
                'choice_label' => 'name',
                'required' => true
            ])
            ->add('figuresImages', CollectionType::class, [
                'label' => false,
                'entry_type' => FiguresImagesType::class,
                'entry_options' => ['label' => false],
                'allow_add' => true,
                'allow_delete' => true
            ])
            ->add('figuresVideos', CollectionType::class, [
                'label' => false,
                'entry_type' => FiguresVideosType::class,
                'entry_options' => ['label' => false],
                'allow_add' => true,
                'allow_delete' => true
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Enregistrer'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Figures::class
        ]);
    }
}
