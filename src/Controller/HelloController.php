<?php

namespace App\Controller;

use http\Header;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * Class HelloController.
 */

#[Route('/hello')]
class HelloController extends AbstractController
{
    /**
     * Index action.
     *
     * @return Response HTTP response
     */
    #[Route(
        '/{name}',
        name: 'hello_index',
        requirements: ['name' => '[a-zA-Z]+'],
        defaults: ['name' => 'World'],
        methods: 'GET')]

    public function index(string $name): Response
    {
//        $name = $request->query->getAlnum('name', 'World');

//        return new Response('Hello '.$name.'!');
        return $this->render(
            'hello/index.html.twig',
            ['name' => $name]
        );
    }
}