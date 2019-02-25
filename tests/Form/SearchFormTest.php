<?php
namespace App\Test\Form;

use App\Form\SearchForm;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchFormTest extends TestCase
{
    
    public function testBuildForm() {
        $builder = $this->createMock(FormBuilderInterface::class);
        $builder->method('add')->willReturn($builder);
        $this->assertNull($this->getSearchForm()->buildForm($builder, []));
    }

    public function testConfigureOptions() {
        $resolver = $this->createMock(OptionsResolver::class);
        $this->assertNull($this->getSearchForm()->configureOptions($resolver));
    }

    public function testGetBlockPrefix() {
        $this->assertNull($this->getSearchForm()->getBlockPrefix());
    }

    private function getSearchForm() {
        $mock = new SearchForm();

        return $mock;
    }

}

