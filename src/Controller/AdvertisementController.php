<?php

namespace App\Controller;

use App\Entity\Advertisement;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
/**
 * Class AdvertisementController
 * @package App\Controller
 *
 * @Route("/advertisement")
 */
class AdvertisementController extends AbstractController {

    /**
     * @param Request $request
     * @Route("/add", name="blog_add", methods={"POST"})
     * @return JsonResponse
     */
    public function add(Request $request)
    {
        $encoders = [new XmlEncoder(), new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];

        $serializer = new Serializer($normalizers, $encoders);
        
        $advertisement = $serializer->deserialize($request->getContent(), Advertisement::class, 'json');

        $em = $this->getDoctrine()->getManager();
        $em->persist($advertisement);
        $em->flush();

        return $this->json($advertisement);
    }

    /**
     * @Route("/{page}", name="AdvertisementList", defaults={"page": 1}, requirements={"page"="\d+"})
     * @param $page
     * @return JsonResponse
     */
    public function listAction($page, Request $request)
    {
        $limit = $request->get('limit', 10);
        $repository = $this->getDoctrine()->getRepository(Advertisement::class);
        $items = $repository->findAll();

        return $this->json(
            [
                'page' => $page,
                'data' => array_map(function (Advertisement $item){
                    return $this->generateUrl('AdvertisementGet', ['id' =>$item->getSlug()]);
                }, $items)
            ]
        );
    }

    /**
     * @Route("/post/{id}", name="AdvertisementGet", requirements={"id"="\d+"})
     * @param $id
     * @return JsonResponse
     */
    public function getAction($id)
    {
        return $this->json(
            $this->getDoctrine()->getRepository(Advertisement::class)->find($id)
        );
    }

    /**
     * @Route("post/{slug}", name="AdvertisementBySlug")
     */
    public function GetBySlug($slug)
    {
        return $this->json(
            $this->getDoctrine()->getRepository(Advertisement::class)->findOneBy(['slug' => $slug])
        );
    }

}