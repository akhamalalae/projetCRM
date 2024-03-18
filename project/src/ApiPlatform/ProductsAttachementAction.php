<?php

namespace App\ApiPlatform;

use App\Entity\Produit;
use App\Entity\Attachement;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[AsController]
final class ProductsAttachementAction extends AbstractController
{

    public function __construct(private EntityManagerInterface $entityManager)
    {
    }

    public function __invoke(Request $request): Produit
    {
        $product = $request->attributes->get('data');
        $files = $request->files->all();

        foreach ($files as $file) {
            $attachment = new Attachement();
            $attachment->setFile($file);
            $this->entityManager->persist($attachment);
            $product->addAttachement($attachment);
        }

        $this->entityManager->flush();

        return $product;
    }
}