<?php

namespace App\Controller;

use App\Entity\Advertisement;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Serializer;

/**
 * @Route("/advertisement")
 */
class AdvertisementController extends AbstractController
{
    /**
     * @Route("/{page}", name="advertisement_list", defaults={"page": 5}, requirements={"page"="\d+"})
     * @param int $page
     * @param Request $request
     * @return JsonResponse
     */
    public function list($page = 1, Request $request)
    {
        $limit = $request->get('limit', 10);
        $repository = $this->getDoctrine()->getRepository(Advertisement::class);
        $items = $repository->findAll();

        return $this->json(
            [
                'page' => $page,
                'limit' => $limit,
                'data' => array_map(function (Advertisement $item) {
                    return $this->generateUrl('advertisement_by_slug', ['slug' => $item->getSlug()]);
                }, $items)
            ]
        );
    }

    /**
     * @Route("/post/{id}", name="advertisement_by_id", requirements={"id"="\d+"}, methods={"GET"})
     * @ParamConverter("post", class="App:Advertisement")
     */
    public function post($post)
    {
        // It's the same as doing find($id) on repository
        return $this->json($post);
    }

    /**
     * @Route("/post/{slug}", name="advertisement_by_slug", methods={"GET"})
     * The below annotation is not required when $post is typehinted with Advertisement
     * and route parameter name matches any field on the Advertisement entity
     * @ParamConverter("post", class="App:Advertisement", options={"mapping": {"slug": "slug"}})
     */
    public function postBySlug(Advertisement $post)
    {
        // Same as doing findOneBy(['slug' => contents of {slug}])
        return $this->json($post);
    }

    /**
     * @Route("/add", name="advertisement_add", methods={"POST"})
     */
    public function add(Request $request)
    {
        /** @var Serializer $serializer */
        $serializer = $this->get('serializer');

        $blogPost = $serializer->deserialize($request->getContent(), Advertisement::class, 'json');

        $em = $this->getDoctrine()->getManager();
        $em->persist($blogPost);
        $em->flush();

        return $this->json($blogPost);
    }

    /**
     * @Route("/post/{id}", name="advertisement_delete", methods={"DELETE"})
     */
    public function delete(Advertisement $post)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($post);
        $em->flush();

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }
}
