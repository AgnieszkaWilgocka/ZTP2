<?php

namespace App\Tests\Form;

use App\Entity\Category;
use App\Form\Type\categoryType;
use Symfony\Component\Form\Test\TypeTestCase;

class CategoryTypeTest extends TypeTestCase
{
    public function testSubmitValidDate(): void
    {
//        $dateTime = new \DateTimeImmutable();
        $formData = [
            'title' => 'TestCategory',
//            'createdAt' => $dateTime,
//            'updatedAt' => $dateTime
        ];

        $model = new Category();
        $form = $this->factory->create(categoryType::class, $model);

        $expected = new Category();
//        $expected->setCreatedAt($dateTime);
//        $expected->setUpdatedAt($dateTime);
        $expected->setTitle('TestCategory');

        $form->submit($formData);
        $this->assertTrue($form->isSynchronized());

        $this->assertEquals($expected, $model);
    }
}