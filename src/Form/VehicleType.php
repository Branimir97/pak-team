<?php

namespace App\Form;

use App\Entity\Vehicle;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VehicleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $required = $options['required'];
        $builder
            ->add('status', ChoiceType::class, [
                'choices' => [
                    'Dostupno' => 'Dostupno',
                    'U dolasku' => 'U dolasku',
                    'Rezervirano' => 'Rezervirano',
                    'Prodano' => 'Prodano'
                ]
            ])
            ->add('type', ChoiceType::class, [
                'choices' => [
                    'Osobno vozilo' => 'Osobno vozilo',
                    'Gospodarsko vozilo' => 'Gospodarsko vozilo',
                ],
                'label'=>'Tip vozila'
            ])
            ->add('mark', TextType::class, [
                'label'=>'Marka vozila',
                'attr'=>['placeholder'=>'npr. Volkswagen']
            ])
            ->add('model', TextType::class, [
                'label'=>"Model vozila",
                'attr'=>['placeholder'=>'npr. Golf VI 1.6 TDI Highline']
            ])
            ->add('engineType', ChoiceType::class, [
                'label'=>'Vrsta motora',
                'choices' => [
                    'Diesel' => 'Diesel ',
                    'Benzin' => 'Benzin',
                    'Električni' => 'Električni',
                    'Hybrid' => 'Hybrid',
                    'Plug-In Hybrid' => 'Plug-In Hybrid',
                ]
            ])
            ->add('manufactureYear', DateType::class, [
                'widget'=>'single_text',
                'label'=>'Godina proizvodnje'
            ])
            ->add('modelYear', DateType::class, [
                'widget'=>'single_text',
                'label'=>'Godina modela'
            ])
            ->add('kilometers', IntegerType::class, [
                'label'=>'Prijeđeni kilometri',
                'attr'=>['placeholder'=>'npr. 204500']
            ])
            ->add('power', IntegerType::class, [
                'label' => "Snaga [kW]",
                'attr'=>['placeholder'=>'npr. 77']
            ])
            ->add('gearbox', ChoiceType::class, [
                'choices' => [
                    'Ručni' => 'Ručni',
                    'Polu-automatski' => 'Polu-automatski',
                    'Automatski' => 'Automatski'
                ],
                'label'=>'Mjenjač'
            ])
            ->add('gears', IntegerType::class, [
                'attr' => [
                    'min' => 4,
                    'max' => 9,
                    'placeholder'=>'npr. 5'
                ],
                'label'=>'Broj brzina'
            ])
            ->add('consumption', NumberType::class, [
                'label'=>'Potrošnja [L/100km]',
                'attr'=>['placeholder'=>'npr. 5.3']
            ])
            ->add('description', TextareaType::class, [
                'label'=>'Opis vozila',
                'attr'=>['rows'=>10, 'placeholder'=>'
                                        npr. Automobil bez ulaganja. Grijanje sjedala, šiber, 
                                        panorama, full LED paket, ...'],
            ])
            ->add('price', NumberType::class, [
                'label'=>'Cijena [€]',
                'attr'=>['placeholder'=>'npr. 8100']
            ]);

        if($required)
        {
            $builder ->add('coverFile', FileType::class, [
                'mapped' => false,
                'label'=>'Naslovna fotografija'
            ]);

            $builder ->add('imageFile', FileType::class, [
                'mapped' => false,
                'multiple' => true,
                'label'=>'Ostale fotografije vozila'
            ]);
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Vehicle::class,
            'required' => true,
        ]);
    }
}
