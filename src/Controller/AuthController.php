<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Swagger\Annotations as SWG;

/**
 * @Route("/auth", name="auth_", methods={"OPTIONS"})
 **/
class AuthController extends AbstractController
{
    /**
     * @Route("/check", name="check", methods={"GET"})
     */
    public function check(): JsonResponse
    {
        $user = $this->getUser();

        return $this->json($user->toArray());
    }
}
