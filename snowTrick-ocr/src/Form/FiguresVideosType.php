<?php

namespace App\Form;

use App\Entity\FiguresVideos;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FiguresVideosType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('site_url', UrlType::class, [
                'label' => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => FiguresVideos::class,
        ]);
    }
}
