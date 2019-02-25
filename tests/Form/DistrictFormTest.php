<?php

namespace App\Test\Form;

use App\Form\DistrictForm;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DistrictFormTest extends TestCase {
    
    public function testBuildForm() {
        $builder = $this->createMock(FormBuilderInterface::class);
        $builder->method('add')->willReturn($builder);
        $this->assertNull($this->getDistrictForm()->buildForm($builder, []));
    }

    public function testConfigureOptions() {
        $resolver = $this->createMock(OptionsResolver::class);
        $this->assertNull($this->getDistrictForm()->configureOptions($resolver));
    }
    
    private function getDistrictForm(){
        $mock = new DistrictForm();
        
        return $mock;
    }

}
