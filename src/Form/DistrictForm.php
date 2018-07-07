<?php

namespace App\Form;

use App\Entity\City;
use App\Entity\District;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DistrictForm extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->add('name')
                ->add('population')
                ->add('area')
        ;

        $builder->add('city', EntityType::class, [
            'label' => 'City',
            'class' => City::class,
            'choice_label' => 'name',
        ]);

        $builder->add('submit', SubmitType::class, array(
            'label' => 'Save',
        ));
    }

    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults([
            'data_class' => District::class,
        ]);
    }

}
