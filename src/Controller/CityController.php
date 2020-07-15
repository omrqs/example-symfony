<?php
namespace App\Controller;

use App\Document\City;
use App\Form\CityType;
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
 * @Route("/city", name="city_")
 */
class CityController extends AbstractController
{
    /**
     * @Route("", name="index", methods={"GET"})
     * @SWG\Response(
     *     response=200,
     *     description="List paginated cities.",
     * )
     * @SWG\Tag(name="city")
     * @NelmioSecurity(name="Bearer")
     */
    public function index(DocumentManager $dm): JsonResponse
    {
        $cities = $dm->getRepository(City::class)->findAll();

        return $this->json([
            'cities' => $cities,
        ]);
    }

    /**
     * @Route("/", name="new", methods={"POST"})
     * @SWG\Response(
     *     response=200,
     *     description="Create a new city",
     *     @SWG\Schema(
     *         type="json",
     *         @SWG\Items(ref=@NelmioModel(type=CityType::class))
     *     )
     * )
     * @SWG\Tag(name="city")
     * @NelmioSecurity(name="Bearer")
     */
    public function new(Request $request, DocumentManager $dm, TranslatorInterface $translator): JsonResponse
    {
        $city = new City();
        $form = $this->createForm(CityType::class, $city);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $dm->persist($city);
            $dm->flush();

            $this->addFlash('error', $translator->trans('controller.success.new', [], 'city'));
        }

        return $this->json([
            'city' => $city,
        ]);
    }

    /**
     * @Route("/{id}", name="show", methods={"GET"})
     * @SWG\Response(
     *     response=200,
     *     description="Show city details.",
     * )
     * @SWG\Tag(name="city")
     * @NelmioSecurity(name="Bearer")
     */
    public function show(DocumentManager $dm, int $id): JsonResponse
    {
        $city = $dm->getRepository(City::class)->find($id);

        return $this->json([
            'city' => $city,
        ]);
    }

    /**
     * @Route("/{id}", name="update", methods={"PATCH"})
     * @SWG\Response(
     *     response=200,
     *     description="Update a city",
     *     @SWG\Schema(
     *         type="json",
     *         @SWG\Items(ref=@NelmioModel(type=CityType::class))
     *     )
     * )
     * @SWG\Tag(name="city")
     * @NelmioSecurity(name="Bearer")
     */
    public function update(Request $request, DocumentManager $dm, int $id, TranslatorInterface $translator): JsonResponse
    {
        $city = $dm->getRepository(City::class)->find($id);

        $form = $this->createForm(CityType::class, $city, ['method' => 'PATCH']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $dm->flush();

            $this->addFlash('error', $translator->trans('controller.success.update', [], 'city'));
        }

        return $this->json([
            'city' => $city,
        ]);
    }

    /**
     * @Route("/{id}", name="delete", methods={"DELETE"})
     * @SWG\Response(
     *     response=200,
     *     description="Delete a city",
     * )
     * @SWG\Tag(name="city")
     * @NelmioSecurity(name="Bearer")
     */
    public function delete(Request $request, DocumentManager $dm, int $id, TranslatorInterface $translator): JsonResponse
    {
        $city = $dm->getRepository(City::class)->find($id);

        try {
            $em = $this->getDoctrine('doctrine_mongodb')->getManager();
            $em->remove($city);
            $em->flush();

            $this->addFlash('error', $translator->trans('controller.success.delete', [], 'city'));
        } catch (\Exception $e) {
            $this->addFlash('error', $translator->trans($e->getMessage(), [], 'city'));
        }

        return $this->json([]);
    }
}
