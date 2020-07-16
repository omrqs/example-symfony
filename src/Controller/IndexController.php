<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Contracts\Translation\TranslatorInterface;
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

    /**
     * @Route("/healthy", name="healthy", methods={"GET"})
     * @SWG\Response(
     *     response=200,
     *     description="Healthy route of API.",
     * )
     * @SWG\Tag(name="main")
     */
    public function healthy(TranslatorInterface $translator)
    {
        $this->addFlash('info', $translator->trans('controller.success.healthy', [], 'main'));

        return $this->json([]);
    }
}
