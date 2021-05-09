<?php

namespace App\Form;

use App\Entity\Inquirie;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class InquirieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('vehicle', TextType::class, [
                'attr'=>[
                    'readonly'=>true
                ],
                'mapped'=>false,
                'label'=>'Vozilo'
            ])
            ->add('firstName', TextType::class, [
                'label'=>'Ime',
                'attr'=>[
                    'placeholder'=>'npr. Ivan'
                ]
            ])
            ->add('lastName', TextType::class, [
                'label'=>'Prezime',
                'attr'=>[
                    'placeholder'=>'npr. Ivić'
                ]
            ])
            ->add('email', EmailType::class, [
                'label'=>'Email adresa',
                'attr'=>[
                    'placeholder'=>'npr. ivan@gmail.com'
                ]
            ])
            ->add('contactNumber', TelType::class, [
                'label'=>'Kontakt broj telefona',
                'attr'=>[
                    'placeholder'=>'npr. 094555799'
                ]
            ])
            ->add('content', TextareaType::class, [
                'label'=>'Sadržaj',
                'attr'=>[
                    'placeholder'=>'npr. Što Vas još zanima o vozilu, načini plaćanja, kupovina vozila, dogovor oko preuzimanja vozila itd.'
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Inquirie::class,
        ]);
    }
}
