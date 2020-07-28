<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Swagger\Annotations as SWG;

/**
 * @Route(methods={"OPTIONS"})
 */
class IndexController extends AbstractController
{
    /**
     * @Route("", name="index", methods={"GET"})
     *
     * @SWG\Response(
     *     response=302,
     *     description="Index route of API. Redirecting to UI API doc.",
     * )
     * @SWG\Tag(name="index")
     */
    public function index(): RedirectResponse
    {
        return $this->redirectToRoute('app.swagger_ui');
    }

    /**
     * @Route("/healthy", name="healthy", methods={"GET"})
     *
     * @SWG\Response(
     *     response=200,
     *     description="Healthy route of API.",
     * )
     * @SWG\Tag(name="healthy")
     */
    public function healthy(TranslatorInterface $translator): JsonResponse
    {
        $this->addFlash('info', $translator->trans('controller.success.healthy', [], 'main'));

        return $this->json([]);
    }
}
