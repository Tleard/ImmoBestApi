<?php


namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * Class UserConfirmation
 * @package App\Entity
 *
 * @ApiResource(
 *     collectionOperations={
 *          "post"= {
 *              "path"="/users/confirm"
 *          }
 *     }
 * )
 */
class UserConfirmation
{
    /**
     * @Assert\NotBlank()
     * @Assert\Length(max=30, min=30)
     */
    public $confirmationToken;
}