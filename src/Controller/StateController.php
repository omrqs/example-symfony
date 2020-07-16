<?php
namespace App\Controller;

use App\Document\State;
use App\Form\StateType;
use Doctrine\ODM\MongoDB\DocumentManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use Nelmio\ApiDocBundle\Annotation\Model as NelmioModel;
use Nelmio\ApiDocBundle\Annotation\Security as NelmioSecurity;
use Swagger\Annotations as SWG;

/**
 * @Route("/state", name="state_")
 */
class StateController extends AbstractController
{
    /**
     * @Route("", name="index", methods={"GET"})
     * @SWG\Response(
     *     response=200,
     *     description="List paginated states.",
     * )
     * @SWG\Tag(name="state")
     * @NelmioSecurity(name="Bearer")
     */
    public function index(DocumentManager $dm): JsonResponse
    {
        $states = $dm->getRepository(State::class)->findAll();
     
        return $this->json([
            'states' => $states,
        ]);
    }

    /**
     * @Route("", name="new", methods={"POST"})
     * @SWG\Response(
     *     response=200,
     *     description="Create a new state",
     *     @SWG\Schema(
     *         type="json",
     *         @SWG\Items(ref=@NelmioModel(type=StateType::class))
     *     )
     * )
     * @SWG\Tag(name="state")
     * @NelmioSecurity(name="Bearer")
     */
    public function new(Request $request, DocumentManager $dm, TranslatorInterface $translator): JsonResponse
    {
        $state = new State();
        $form = $this->createForm(StateType::class, $state);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $dm->persist($state);
            $dm->flush();

            $this->addFlash('error', $translator->trans('controller.success.new', [], 'state'));
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
     * @NelmioSecurity(name="Bearer")
     */
    public function show(DocumentManager $dm, int $id): JsonResponse
    {
        $state = $dm->getRepository(State::class)->find($id);

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
     * @NelmioSecurity(name="Bearer")
     */
    public function update(Request $request, DocumentManager $dm, int $id, TranslatorInterface $translator): JsonResponse
    {
        $state = $dm->getRepository(State::class)->find($id);

        $form = $this->createForm(StateType::class, $state, ['method' => 'PATCH']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $dm->flush();

            $this->addFlash('error', $translator->trans('controller.success.update', [], 'state'));
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
     * @NelmioSecurity(name="Bearer")
     */
    public function delete(Request $request, DocumentManager $dm, int $id, TranslatorInterface $translator): JsonResponse
    {
        $state = $dm->getRepository(State::class)->find($id);

        try {
            $dm->remove($state);
            $dm->flush();
        } catch (\Exception $e) {
            $this->addFlash('error', $translator->trans($e->getMessage(), [], 'state'));
        }

        return $this->json([]);
    }
}
