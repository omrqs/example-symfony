<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Swagger\Annotations as SWG;

class IndexController extends AbstractController
{
    /**
     * @Route("", name="index", methods={"GET"})
     * @SWG\Response(
     *     response=200,
     *     description="Index route of API.",
     * )
     * @SWG\Tag(name="main")
     */
    public function index()
    {
        return $this->redirectToRoute('app.swagger_ui');
    }
}
