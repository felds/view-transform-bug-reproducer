<?php
declare(strict_types=1);

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends Controller
{
    /**
     * @Route("/")
     * @Template()
     */
    public function indexAction(Request $request)
    {
        $formBuilder = $this->createFormBuilder()
            ->add('name', TextType::class)
            ->add('submit', SubmitType::class)
            ->addViewTransformer(new ViewTransformer())
        ;

        $form = $formBuilder->getForm();
        $form->handleRequest($request);

        return ['form' => $form->createView()];
    }
}

class User
{
    private $name;

    public function __construct($name)
    {
        $this->name = strtoupper($name);
    }

    public function getName()
    {
        return $this->name;
    }
}

class ViewTransformer implements DataTransformerInterface
{
    public function transform($value)
    {
        $out = ($value instanceof User)
            ? ['name' => $value->getName()]
            : ['name' => ""];

        dump(['f' => 'transform', 'in' => $value, 'out' => $out]);

        return $out;
    }

    public function reverseTransform($value)
    {
        $out = new User($value['name']);

        dump(['f' => 'reverse transform', 'in' => $value, 'out' => $out]);

        return $out;
    }
}