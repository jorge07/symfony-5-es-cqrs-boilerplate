<?php

declare(strict_types=1);

namespace App\UI\Http\Web\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

class HomeController extends AbstractRenderController
{
    /**
     * @Route(
     *     "/",
     *     name="home",
     *     methods={"GET"}
     * )
     *
     * @return Response
     */
    public function get(): Response
    {
        return $this->render('home/index.html.twig');
    }
}
