<?php

namespace App\Controller;

use App\Entity\Advertisement;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
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
     * @throws Exception
     */
    public function CreateAction(Request $request)
    {
        $encoders = [new XmlEncoder(), new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];

        $serializer = new Serializer($normalizers, $encoders);

        $advertisement = $serializer->deserialize($request->getContent(), Advertisement::class, 'json');

        $em = $this->getDoctrine()->getManager();
        $advertisement->setPublished(new \DateTime());
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
                    return $this->generateUrl('AdvertisementGet', ['id' =>$item->getId()]);
                }, $items)
            ]
        );
    }

    /**
     * @Route("/post/{id}", name="AdvertisementGet", requirements={"id"="\d+"}, methods={"GET"})
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

    /**
     * @param Advertisement $advertisement
     * @Route("/post/{id}", name="AdvertisementDelete", methods={"DELETE"})
     * @return JsonResponse
     */
    public function DeleteAction(Advertisement $advertisement)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($advertisement);
        $em->flush();

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }

}