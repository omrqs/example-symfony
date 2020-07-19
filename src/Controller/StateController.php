<?php
namespace App\Controller;

use App\Entity\State;
use App\Form\StateType;
use Doctrine\ODM\MongoDB\DocumentManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use Knp\Component\Pager\PaginatorInterface;
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
     *
     * @SWG\Response(
     *     response=200,
     *     description="List states paginated.",
     * )
     * @SWG\Parameter(
     *     name="custom fields",
     *     in="query",
     *     type="string",
     *     description="Field name to filter. Ex.: name=lorem"
     * )
     * @SWG\Parameter(
     *     name="order_by",
     *     in="query",
     *     type="string",
     *     description="Field to order"
     * )
     * @SWG\Parameter(
     *     name="order",
     *     in="query",
     *     type="string",
     *     description="Ordering direction"
     * )
     * @SWG\Parameter(
     *     name="limit",
     *     in="query",
     *     type="integer",
     *     description="Limit of result paginate"
     * )
     * @SWG\Parameter(
     *     name="page",
     *     in="query",
     *     type="integer",
     *     description="Page of pagination"
     * )
     * @SWG\Tag(name="state")
     *
     * @NelmioSecurity(name="Bearer")
     */
    public function index(Request $request, PaginatorInterface $paginator): JsonResponse
    {
        // pagination
        $pagination = $paginator->paginate(
            $this->getDoctrine()->getRepository(State::class)->queryToPaginate($request->query->all()),
            $request->query->get('page', 1),
            $request->query->get('limit', getenv('PAGINATOR_LIMIT_PER_REQUEST'))
        );
        // end pagination

        return $this->json([
            'data' => ['states' => \App\Helper\CoreHelper::objectsToArray($pagination->getItems())],
            'paginator' => $pagination->getPaginationData(),
        ]);
    }

    /**
     * @Route("", name="new", methods={"POST"})
     *
     * @SWG\Response(
     *     response=200,
     *     description="Create a new state",
     *     @SWG\Schema(@SWG\Items(ref=@NelmioModel(type=StateType::class)))
     * )
     * @SWG\Tag(name="state")
     *
     * @NelmioSecurity(name="Bearer")
     */
    public function new(Request $request, TranslatorInterface $translator): JsonResponse
    {
        $state = new State();
        $form = $this->createForm(StateType::class, $state);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($state);
            $em->flush();

            $this->addFlash('success', $translator->trans('controller.success.new', [], 'state'));
        } else {
            foreach ($form->getErrors(true) as $key => $error) {
                $this->addFlash('error', $translator->trans($error->getMessage(), [], 'state'));
            }
        }

        return $this->json([
            'state' => $state->toArray(),
        ]);
    }

    /**
     * @Route("/{id}", name="show", methods={"GET"})
     *
     * @SWG\Response(
     *     response=200,
     *     description="Show state details.",
     * )
     * @SWG\Tag(name="state")
     *
     * @NelmioSecurity(name="Bearer")
     */
    public function show(State $state): JsonResponse
    {
        return $this->json([
            'state' => $state->toArray(),
        ]);
    }

    /**
     * @Route("/{id}", name="update", methods={"PATCH"})
     *
     * @SWG\Response(
     *     response=200,
     *     description="Update a state",
     *     @SWG\Schema(@SWG\Items(ref=@NelmioModel(type=StateType::class)))
     * )
     * @SWG\Tag(name="state")
     *
     * @NelmioSecurity(name="Bearer")
     */
    public function update(Request $request, State $state, TranslatorInterface $translator): JsonResponse
    {
        $form = $this->createForm(StateType::class, $state, ['method' => 'PATCH']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', $translator->trans('controller.success.update', [], 'state'));
        } else {
            foreach ($form->getErrors(true) as $key => $error) {
                $this->addFlash('error', $translator->trans($error->getMessage(), [], 'state'));
            }
        }

        return $this->json([
            'state' => $state->toArray(),
        ]);
    }

    /**
     * @Route("/{id}", name="delete", methods={"DELETE"})
     *
     * @SWG\Response(
     *     response=200,
     *     description="Delete a state",
     * )
     * @SWG\Tag(name="state")
     *
     * @NelmioSecurity(name="Bearer")
     */
    public function delete(State $state, TranslatorInterface $translator): JsonResponse
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($state);
        $em->flush();

        $this->addFlash('success', $translator->trans('controller.success.delete', [], 'state'));

        return $this->json([]);
    }
}
