<?php

namespace App\Form;

use App\Entity\Event;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EventType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title')
            ->add('description')
            ->add('date', DateTimeType::class, [
                'widget' => 'single_text',
                'html5' => true,  // Keep html5 true for better browser support
                // Remove the 'format' option when using html5
            ])
            ->add('location')
            ->add('seats')
            ->add('image', FileType::class, [
                'label' => 'Event image',
                'mapped' => false,
                'required' => false,
                'attr' => ['accept' => 'image/*'],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Event::class,
        ]);
    }
}