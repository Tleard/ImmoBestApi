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

    private const POSTS = [
    [
        'id' => 1,
        'slug' => 'hello-world',
        'title' => 'Hello world!'
    ],
    [
        'id' => 2,
        'slug' => 'another-post',
        'title' => 'This is another post!'
    ],
    [
        'id' => 3,
        'slug' => 'last-example',
        'title' => 'This is the last example'
    ]
    ];


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
    public function listAction($page)
    {
        return $this->json(
            [
                'page' => $page,
                'data' => array_map(function ($item){
                    return $this->generateUrl('AdvertisementPost', ['id' =>$item['id']]);
                }, self::POSTS)
            ]
        );
    }

    /**
     * @Route("/{id}", name="AdvertisementPost", requirements={"id"="\d+"})
     * @param $id
     * @return JsonResponse
     */
    public function postAction($id)
    {
        return $this->json(
            self::POSTS[array_search($id, array_column(self::POSTS, 'id'))]
        );
    }

    /**
     * @Route("/{slug}", name="AdvertisementBySlug")
     */
    public function PostBySlug($slug)
    {
        return $this->json(
            self::POSTS[array_search($slug, array_column(self::POSTS, 'slug'))]
        );
    }

}