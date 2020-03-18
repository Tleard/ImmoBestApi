<?php


namespace App\Controller;


use ApiPlatform\Core\Validator\Exception\ValidationException;
use ApiPlatform\Core\Validator\ValidatorInterface;
use App\Entity\Image;
use App\Form\ImageType;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;

class UploadImageAction
{
    /**
     * @var FormFactoryInterface
     */
    private $formFactory;

    /**
     * @var EntityManagerInterface
     */
    private $em;
    /**
     * @var ValidatorInterface
     */
    private $validatorInterface;

    /**
     * UploadImageAction constructor.
     * @param FormFactoryInterface $formFactory
     * @param EntityManagerInterface $em
     * @param ValidatorInterface $validator
     */
    public function __construct(
        FormFactoryInterface $formFactory,
        EntityManagerInterface $em,
        ValidatorInterface $validator)
    {
        $this->formFactory = $formFactory;
        $this->em = $em;
        $this->validatorInterface = $validator;
    }

    public function __invoke(Request $request)
    {
        //create a new Image instance
        $image = new Image();

        //Validate Form
        $form = $this->formFactory->create(ImageType::class, $image);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //Persist the new Image entity
            $this->em->persist($image);
            $this->em->flush();

            $image->setFile(null);

            return $image;
        }

        //form validation
        throw new ValidationException(
            $this->validatorInterface->validate($image)
        );


        //Persist Image Entity

        //Uploading complete by VichUploader
    }
}