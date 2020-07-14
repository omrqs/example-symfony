<?php

namespace App\Controller;

use App\Entity\State;
use App\Form\StateType;
use App\DocumentRepository\StateRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
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
     *     description="List paginate cities.",
     * )
     * @SWG\Tag(name="state")
     */
    public function index(StateRepository $stateRepository): JsonResponse
    {
        $cities = $stateRepository->findAll();

        return $this->json([
            'cities' => $cities,
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
            $em = $this->getDoctrine()->getManager();
            $em->persist($state);
            $em->flush();
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
     * @Entity("state", expr="repository.find(id)")
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
     * @Entity("state", expr="repository.find(id)")
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
     * @Entity("state", expr="repository.find(id)")
     */
    public function delete(Request $request, State $state): JsonResponse
    {
            $em = $this->getDoctrine()->getManager();
            $em->remove($state);
            $em->flush();
        }

        return $this->json([]);
    }
}
