<?php

namespace App\Controller;

use App\Entity\City;
use App\Form\CityType;
use App\DocumentRepository\CityRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @Route("/city", name="city_")
 */
class CityController extends AbstractController
{
    /**
     * @Route("/", name="index", methods={"GET"})
     * @SWG\Response(
     *     response=200,
     *     description="List paginate cities.",
     * )
     * @SWG\Tag(name="city")
     */
    public function index(CityRepository $cityRepository): JsonResponse
    {
        $cities = $cityRepository->findAll();

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
     * @Entity("city", expr="repository.find(id)")
     */
    public function show(City $city): JsonResponse
    {
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
     * @Entity("city", expr="repository.find(id)")
     */
    public function update(Request $request, City $city, TranslatorInterface $translator): JsonResponse
    {
        $form = $this->createForm(CityType::class, $city, ['method' => 'PATCH']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

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
     * @Entity("city", expr="repository.find(id)")
     */
    public function delete(Request $request, City $city, TranslatorInterface $translator): JsonResponse
    {
        try {
            $em = $this->getDoctrine()->getManager();
            $em->remove($city);
            $em->flush();

            $this->addFlash('error', $translator->trans('controller.success.delete', [], 'city'));
        } catch (\Exception $e) {
            $this->addFlash('error', $translator->trans($e->getMessage(), [], 'city'));
        }

        return $this->json([]);
    }
}
