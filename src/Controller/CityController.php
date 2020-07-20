<?php
namespace App\Controller;

use App\Entity\City;
use App\Form\CityType;
use App\Repository\CityRepository;
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
 * @Route("/city", name="city_", methods={"OPTIONS"})
 */
class CityController extends AbstractController
{
    /**
     * @Route("", name="index", methods={"GET"})
     *
     * @SWG\Response(
     *     response=200,
     *     description="List cities paginated.",
     * )
     * @SWG\Parameter(
     *     name="custom fields",
     *     in="query",
     *     type="string",
     *     description="Field name to filter. Ex.: name=lorem"
     * )
     * @SWG\Parameter(
     *     name="sort",
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
     * @SWG\Tag(name="city")
     *
     * @NelmioSecurity(name="Bearer")
     */
    public function index(Request $request, CityRepository $cityRepository, PaginatorInterface $paginator): JsonResponse
    {
        // pagination
        $pagination = $paginator->paginate(
            $cityRepository->queryToPaginate($request->query->all()),
            (int) $request->query->get('page', "1"),
            (int) $request->query->get('limit', (string) getenv('PAGINATOR_LIMIT_PER_REQUEST'))
        );
        // end pagination

        return $this->json([
            'data' => ['cities' => \App\Helper\CoreHelper::objectsToArray($pagination->getItems())],
            'paginator' => $pagination->getPaginationData(),
        ]);
    }

    /**
     * @Route("", name="new", methods={"POST"})
     *
     * @SWG\Response(
     *     response=200,
     *     description="Create a new city",
     *     @SWG\Schema(@SWG\Items(ref=@NelmioModel(type=CityType::class)))
     * )
     * @SWG\Tag(name="city")
     *
     * @NelmioSecurity(name="Bearer")
     */
    public function new(Request $request, TranslatorInterface $translator): JsonResponse
    {
        $city = new City();
        $form = $this->createForm(CityType::class, $city);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($city);
            $em->flush();

            $this->addFlash('success', $translator->trans('controller.success.new', [], 'city'));
        } else {
            foreach ($form->getErrors(true) as $key => $error) {
                $this->addFlash('error', $translator->trans($error->getMessage(), [], 'city'));
            }
        }

        return $this->json([
            'city' => $city->toArray(),
        ]);
    }

    /**
     * @Route("/{id}", name="show", methods={"GET"})
     *
     * @SWG\Response(
     *     response=200,
     *     description="Show city details.",
     * )
     * @SWG\Tag(name="city")
     *
     * @NelmioSecurity(name="Bearer")
     */
    public function show(City $city): JsonResponse
    {
        return $this->json([
            'city' => $city->toArray(),
        ]);
    }

    /**
     * @Route("/{id}", name="update", methods={"PATCH"})
     *
     * @SWG\Response(
     *     response=200,
     *     description="Update a city",
     *     @SWG\Schema(@SWG\Items(ref=@NelmioModel(type=CityType::class)))
     * )
     * @SWG\Tag(name="city")
     *
     * @NelmioSecurity(name="Bearer")
     */
    public function update(Request $request, City $city, TranslatorInterface $translator): JsonResponse
    {
        $form = $this->createForm(CityType::class, $city, ['method' => 'PATCH']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', $translator->trans('controller.success.update', [], 'city'));
        }

        return $this->json([
            'city' => $city->toArray(),
        ]);
    }

    /**
     * @Route("/{id}", name="delete", methods={"DELETE"})
     *
     * @SWG\Response(
     *     response=200,
     *     description="Delete a city",
     * )
     * @SWG\Tag(name="city")
     *
     * @NelmioSecurity(name="Bearer")
     */
    public function delete(City $city, TranslatorInterface $translator): JsonResponse
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($city);
        $em->flush();
        
        $this->addFlash('success', $translator->trans('controller.success.delete', [], 'city'));

        return $this->json([]);
    }
}
