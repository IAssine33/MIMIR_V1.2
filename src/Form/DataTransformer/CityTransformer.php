<?php

namespace App\Form\DataTransformer;

use App\Entity\City;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\DataTransformerInterface;



class CityTransformer implements DataTransformerInterface
{
    private $entityManager;
    // la methode consructeur est appeler toujours en premier
    // en appellant n'imprte quelle methode dans le meme controller
    public function __construct(EntityManagerInterface $entity)
    {
        $this->entityManager = $entity;
    }


     /* Transforme une entité City en un tableau avec 'zip_code' et 'name' */

     function transform($city): array
    {
        if ($city === null) {
            return [
            // retourne des champs vides
                'zip_code' => '',
                'name' => '',
            ];
        }
    // sinon ce qu'il y'a dedans
        return [
            'zip_code' => $city->getZipCode(),
            'name' => $city->getName(),
        ];
    }


     /* Transforme les données du formulaire en une entité City */
    public function reverseTransform($data): mixed
    {
        if (!$data['zip_code'] || !$data['name']) {
            return null;
        }

        // Cherche la ville par code postal et nom
        $city = $this->entityManager->getRepository(City::class)
            ->findOneBy([
                'zip_code' => $data['zip_code'],
                'name' => $data['name']
            ]);

        // Si la ville n'existe pas, on la crée
        if ($city === null) {
            $city = new City();
            $city->setZipCode($data['zip_code']);
            $city->setName($data['name']);
            $this->entityManager->persist($city);
        }

        return $city;
    }
}