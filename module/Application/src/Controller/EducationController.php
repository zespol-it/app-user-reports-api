<?php
namespace Application\Controller;

use Laminas\Mvc\Controller\AbstractRestfulController;
use Laminas\View\Model\JsonModel;
use Doctrine\ORM\EntityManagerInterface;
use Application\Entity\Education;
use Laminas\Http\Response;

class EducationController extends AbstractRestfulController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function getList()
    {
        $educations = $this->entityManager->getRepository(Education::class)->findAll();
        $educationsArr = array_map(function($e) {
            return [
                'id' => $e->getId(),
                'name' => $e->getName()
            ];
        }, $educations);
        return new JsonModel($educationsArr);
    }

    public function get($id)
    {
        $education = $this->entityManager->getRepository(Education::class)->find($id);
        if (!$education) {
            return $this->getResponse()->setStatusCode(Response::STATUS_CODE_404);
        }
        return new JsonModel($education);
    }

    public function create($data)
    {
        $education = new Education();
        $education->setName($data['name'] ?? null);
        $this->entityManager->persist($education);
        $this->entityManager->flush();
        return new JsonModel(['id' => $education->getId()]);
    }

    public function update($id, $data)
    {
        $education = $this->entityManager->getRepository(Education::class)->find($id);
        if (!$education) {
            return $this->getResponse()->setStatusCode(Response::STATUS_CODE_404);
        }
        if (isset($data['name'])) $education->setName($data['name']);
        $this->entityManager->flush();
        return new JsonModel(['status' => 'updated']);
    }

    public function delete($id)
    {
        $education = $this->entityManager->getRepository(Education::class)->find($id);
        if (!$education) {
            return $this->getResponse()->setStatusCode(Response::STATUS_CODE_404);
        }
        $this->entityManager->remove($education);
        $this->entityManager->flush();
        return new JsonModel(['status' => 'deleted']);
    }

    public function seedAction()
    {
        $examples = ['Podstawowe', 'Średnie', 'Wyższe', 'Zawodowe'];
        foreach ($examples as $name) {
            $education = new Education();
            $education->setName($name);
            $this->entityManager->persist($education);
        }
        $this->entityManager->flush();
        return new JsonModel(['status' => 'seeded']);
    }
} 