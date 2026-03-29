<?php

namespace App\Form;

use App\Entity\Event;
use App\Entity\Reservation;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReservationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Full name',
                'attr' => [
                    'placeholder' => 'Your full name',
                    'class' => 'form-group',
                ],
            ])
            ->add('email', EmailType::class, [
                'label' => 'Email address',
                'attr' => [
                    'placeholder' => 'you@example.com',
                ],
            ])
            ->add('phone', TelType::class, [
                'label' => 'Phone number',
                'attr' => [
                    'placeholder' => '+216 XX XXX XXX',
                ],
            ])
        ;
        // ❌ Removed: createdAt (set in controller)
        // ❌ Removed: event (pre-filled from route {id})
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Reservation::class,
        ]);
    }
}