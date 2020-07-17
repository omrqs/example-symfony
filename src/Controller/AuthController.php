<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Swagger\Annotations as SWG;

/**
 * @Route("/auth", name="auth_", methods={"GET"})
 **/
class AuthController extends AbstractController
{
    /**
     * @Route("/check", name="check", methods={"GET"})
     */
    public function session(TranslatorInterface $translator)
    {
        $this->addFlash('info', $translator->trans('controller.success.session', [], 'auth'));

        return $this->json([]);
    }
}
