<?php

namespace App\Form;

use App\Entity\Image;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ImageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
            "label" => "Le nom:",
            ])
            ->add('url')
            ->add('restaurant', EntityType::class, [
                "multiple" => false,
                "expanded" => false, // radiobutton
                "class" => Category::class,
                'choice_label' => 'name',
            ]);

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Image::class,
            "attr" => ["novalidate" => 'novalidate']

        ]);
    }
}
