<?php


namespace App\Entity;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Validator\Constraints as Assert;
use App\Controller\UploadImageAction;


/**
 * Class Image
 * @package App\Entity
 * @ORM\Entity()
 * @Vich\Uploadable()
 * @ApiResource(
 *    collectionOperations={
 *          "get",
*          "post"={
*         "method"="POST",
*         "path"="/images",
*         "controller"=UploadImageAction::class,
*         "defaults"={"_api_receive"=false},
*         "validation_groups"={"media_object_post"},
*         "swagger_context" = {
*            "consumes" = {
*                "multipart/form-data",
*             }
*         }
*     }
 *   }
 * )
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
     * @ORM\Column(nullable=true)
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