<?php

namespace App\Form;

use App\Entity\SoldVehicle;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class InsertSoldVehiclesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder ->add('imageFile', FileType::class, [
            'mapped' => false,
            'multiple' => true,
            'label'=>'Fotografije prodanih vozila (slike manje od 2MB, max oko 20 odjednom)'
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class'=>SoldVehicle::class
        ]);
    }
}
