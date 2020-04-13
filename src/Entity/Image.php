<?php


namespace App\Entity;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use Doctrine\ORM\Mapping as ORM;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Validator\Constraints as Assert;
use App\Controller\UploadImageAction;
use Symfony\Component\Serializer\Annotation\Groups;



/**
* Class Image
* @package App\Entity
* @ORM\Entity()
* @Vich\Uploadable()
* @ApiResource(
*    attributes={"order"={"id": "ASC"}},
*    collectionOperations={
*          "get"={
 *             "normalization_context"={
 *                 "groups"={"get-blog-post-with-author"}
 *             }
 *     },
*          "post"={
*               "method"="POST",
*               "path"="/images",
*               "controller"=UploadImageAction::class,
*               "defaults"={"_api_receive"=false},
*               "validation_groups"={"media_object_post"},
*               "swagger_context" = {
*                   "consumes" = {
*                       "multipart/form-data",
*                   }
*               }
*         }
*    }
*)
*/
class Image
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Vich\UploadableField(mapping="images", fileNameProperty="url")
     * @Assert\NotNull()
     */
    private $file;

    /**
     *@ORM\Column(nullable=true)
     *@ApiSubresource()
     *@Groups({"get-blog-post-with-author", "get-author"})
     */
    private $url;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id): void
    {
        $this->id = $id;
    }

    public function getFile()
    {
        return $this->file;
    }

    public function setFile($file): void
    {
        $this->file = $file;
    }

    public function getUrl()
    {
        return $this->url;
    }

    public function setUrl($url): void
    {
        $this->url = $url;
    }

}