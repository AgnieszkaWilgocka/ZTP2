<?php
//
//namespace App\Tests\Form;
//
//use App\Entity\Book;
//use App\Entity\Favourite;
//use App\Form\Type\FavouriteType;
//use Symfony\Component\Form\Test\TypeTestCase;
//
//class FavouriteTypeTest extends TypeTestCase
//{
//    public function testSubmitValidData(): void
//    {
//        $bookField = new Book();
//        $bookField->setTitle('new');
//
//        $formData = [
//            'book' => $bookField
//        ];
//
//        $model = new Favourite();
//        $form = $this->factory->create(FavouriteType::class, $model);
//
//        $expected = new Favourite();
//        $expected->setBook($bookField);
//
//        $form->submit($formData);
//        $this->assertTrue($form->isSynchronized());
//
//        $this->assertEquals($expected, $model);
//    }
//}