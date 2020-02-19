<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

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
     * @Route("/{page}", name="AdvertisementList", defaults={"page": 1})
     * @param $page
     * @return JsonResponse
     */
    public function listAction($page)
    {
        return $this->json(
            [
                'page' => $page,
                'data' => array_map(function ($item){
                    return $this->generateUrl('blog_by_id', ['id' =>$item['id']]);
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