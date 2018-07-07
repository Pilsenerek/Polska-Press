<?php
namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchForm extends AbstractType
{
    
    public function buildForm(FormBuilderInterface $builder, array $options) {

        $builder->setMethod('GET');

        $builder->add('col', ChoiceType::class, [
            'choices' => [
                'name' => 'a.name',
                'city' => 'b.name',
                'id' => 'a.id',
                'population' => 'a.population',
                'area' => 'a.area',
            ],
            'placeholder' => 'select column',
            'label' => 'Column',
            'attr' => ['class' => 'ml-2 mr-3'],
        ]);

        $builder->add('search', null, [
            'label' => 'search',
            'attr' => ['placeholder' => 'search text', 'class' => 'ml-2 mr-2'],
        ]);

        $builder->add('submit', SubmitType::class, array(
            'label' => 'Search',
        ));
    }

    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults([
            'csrf_protection' => false,
            'allow_extra_fields' => true,
            'attr' => ['class' => 'form-inline'],
        ]);
    }

    public function getBlockPrefix() {

        return null;
    }

}

