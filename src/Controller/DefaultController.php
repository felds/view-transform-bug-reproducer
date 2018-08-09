<?php
declare(strict_types=1);

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
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

class Model
{
    public $givenName;

    public $familyName;
}

class ViewTransformer implements DataTransformerInterface
{
    public function transform($value)
    {
        dump(['transform', $value]);
        if ($value instanceof Model) {
            return [
                'name' => trim(sprintf('%s %s',
                    $value->givenName,
                    $value->familyName
                )),
            ];
        } else {
            return ['name' => ""];
        }
    }

    public function reverseTransform($value)
    {
        dump(['reverse transform', $value]);
        $name = $value['name'];
        $model = new Model();

        if (preg_match('{^(?<given>\w+)\s(?<family>\w+)$}', $name, $matches)) {
            $model->givenName = $matches['given'];
            $model->familyName = strrev($matches['family']);
        }

        return $model;
    }
}