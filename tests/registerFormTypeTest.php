<?php
/**
 * Created by PhpStorm.
 * User: camille
 * Date: 30/10/2020
 * Time: 09:43
 */

namespace App\Tests;


use App\Entity\User;
use App\Form\RegisterFormType;
use Symfony\Component\Form\Test\TypeTestCase;


class registerFormTypeTest extends TypeTestCase
{
    public function  testSubmitValidData(){
        $formData = [
            "email" => "sdqs@sdqs.com",
            "password" => "sdqsd",
            "summoner_lol" => "dsqsd"
        ];
        $model = new User();
        $form = $this->factory->create(RegisterFormType::class, $model);

        $expected = new User();
        $form->submit($formData);

        // This check ensures there are no transformation failures
        $this->assertTrue($form->isSynchronized());

        // check that $formData was modified as expected when the form was submitted
        $this->assertEquals($expected, $model);
}

}