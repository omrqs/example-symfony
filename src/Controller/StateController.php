<?php

namespace App\Controller;

use App\Entity\State;
use App\Form\StateType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/state", name="state_")
 */
class StateController extends AbstractController
{
    /**
     * @Route("/", name="index", methods={"GET"})
     * @SWG\Response(
     *     response=200,
     *     description="List paginate states.",
     * )
     * @SWG\Tag(name="state")
     */
    public function index(): JsonResponse
    {
        $states = $this->getDoctrine()
            ->getRepository(State::class)
            ->findAll();

        return $this->json([
            'states' => $states,
        ]);
    }

    /**
     * @Route("/", name="new", methods={"POST"})
     * @SWG\Response(
     *     response=200,
     *     description="Create a new state",
     *     @SWG\Schema(
     *         type="json",
     *         @SWG\Items(ref=@NelmioModel(type=StateType::class))
     *     )
     * )
     * @SWG\Tag(name="state")
     */
    public function new(Request $request): JsonResponse
    {
        $state = new State();
        $form = $this->createForm(StateType::class, $state);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($state);
            $entityManager->flush();
        }

        return $this->json([
            'state' => $state,
        ]);
    }

    /**
     * @Route("/{id}", name="show", methods={"GET"})
     * @SWG\Response(
     *     response=200,
     *     description="Show state details.",
     * )
     * @SWG\Tag(name="state")
     */
    public function show(State $state): JsonResponse
    {
        return $this->json([
            'state' => $state,
        ]);
    }

    /**
     * @Route("/{id}", name="update", methods={"PATCH"})
     * @SWG\Response(
     *     response=200,
     *     description="Update a state",
     *     @SWG\Schema(
     *         type="json",
     *         @SWG\Items(ref=@NelmioModel(type=StateType::class))
     *     )
     * )
     * @SWG\Tag(name="state")
     */
    public function update(Request $request, State $state): JsonResponse
    {
        $form = $this->createForm(StateType::class, $state, ['method' => 'PATCH']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
        }

        return $this->json([
            'state' => $state,
        ]);
    }

    /**
     * @Route("/{id}", name="delete", methods={"DELETE"})
     * @SWG\Response(
     *     response=200,
     *     description="Delete a state",
     * )
     * @SWG\Tag(name="state")
     */
    public function delete(Request $request, State $state): JsonResponse
    {
        if ($this->isCsrfTokenValid('delete'.$state->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($state);
            $entityManager->flush();
        }

        return $this->json([]);
    }
}
