<?php
namespace Application\Controller;

use Laminas\Mvc\Controller\AbstractRestfulController;
use Laminas\View\Model\JsonModel;
use Doctrine\ORM\EntityManagerInterface;
use Application\Entity\User;
use Application\Entity\Education;
use Laminas\Http\Response;

class UserController extends AbstractRestfulController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function getList()
    {
        $sort = $this->params()->fromQuery('sort', 'id');
        $order = strtoupper($this->params()->fromQuery('order', 'ASC')) === 'DESC' ? 'DESC' : 'ASC';
        $allowed = ['id', 'name', 'phone_number', 'address', 'age'];
        if (!in_array($sort, $allowed)) {
            $sort = 'id';
        }
        $qb = $this->entityManager->getRepository(User::class)
            ->createQueryBuilder('u');
        // Filtrowanie
        $filters = [
            'name' => $this->params()->fromQuery('filter_name'),
            'phone_number' => $this->params()->fromQuery('filter_phone_number'),
            'address' => $this->params()->fromQuery('filter_address'),
            'age' => $this->params()->fromQuery('filter_age'),
        ];
        foreach ($filters as $field => $value) {
            if ($value !== null && $value !== '') {
                if ($field === 'age') {
                    $qb->andWhere('u.' . $field . ' = :' . $field)
                        ->setParameter($field, $value);
                } else {
                    $qb->andWhere('u.' . $field . ' LIKE :' . $field)
                        ->setParameter($field, '%' . $value . '%');
                }
            }
        }
        $page = max(1, (int)$this->params()->fromQuery('page', 1));
        $limit = max(1, (int)$this->params()->fromQuery('limit', 10));
        $qb->setFirstResult(($page - 1) * $limit)
           ->setMaxResults($limit);
        $users = $qb->getQuery()->getResult();
        $usersArr = array_map(function($u) {
            return [
                'id' => $u->getId(),
                'name' => $u->getName(),
                'phone_number' => $u->getPhoneNumber(),
                'address' => $u->getAddress(),
                'age' => $u->getAge(),
                'education' => $u->getEducation() ? [
                    'id' => $u->getEducation()->getId(),
                    'name' => $u->getEducation()->getName()
                ] : null
            ];
        }, $users);
        return new JsonModel([
            'page' => $page,
            'limit' => $limit,
            'count' => count($usersArr),
            'data' => $usersArr
        ]);
    }

    public function get($id)
    {
        $user = $this->entityManager->getRepository(User::class)->find($id);
        if (!$user) {
            return $this->getResponse()->setStatusCode(Response::STATUS_CODE_404);
        }
        return new JsonModel($user);
    }

    public function create($data)
    {
        $user = new User();
        $user->setName($data['name'] ?? null);
        $user->setPhoneNumber($data['phone_number'] ?? null);
        $user->setAddress($data['address'] ?? null);
        $user->setAge($data['age'] ?? null);
        if (isset($data['education_id'])) {
            $education = $this->entityManager->getRepository(Education::class)->find($data['education_id']);
            $user->setEducation($education);
        }
        $this->entityManager->persist($user);
        $this->entityManager->flush();
        return new JsonModel(['id' => $user->getId()]);
    }

    public function update($id, $data)
    {
        $user = $this->entityManager->getRepository(User::class)->find($id);
        if (!$user) {
            return $this->getResponse()->setStatusCode(Response::STATUS_CODE_404);
        }
        if (isset($data['name'])) $user->setName($data['name']);
        if (isset($data['phone_number'])) $user->setPhoneNumber($data['phone_number']);
        if (isset($data['address'])) $user->setAddress($data['address']);
        if (isset($data['age'])) $user->setAge($data['age']);
        if (isset($data['education_id'])) {
            $education = $this->entityManager->getRepository(Education::class)->find($data['education_id']);
            $user->setEducation($education);
        }
        $this->entityManager->flush();
        return new JsonModel(['status' => 'updated']);
    }

    public function delete($id)
    {
        $user = $this->entityManager->getRepository(User::class)->find($id);
        if (!$user) {
            return $this->getResponse()->setStatusCode(Response::STATUS_CODE_404);
        }
        $this->entityManager->remove($user);
        $this->entityManager->flush();
        return new JsonModel(['status' => 'deleted']);
    }

    public function seedAction()
    {
        $educationRepo = $this->entityManager->getRepository(Education::class);
        $educations = $educationRepo->findAll();
        if (empty($educations)) {
            return new JsonModel(['error' => 'Najpierw dodaj typy wykształcenia!']);
        }
        $examples = [
            ['Jan Kowalski', '123456789', 'Warszawa', 30, $educations[0]],
            ['Anna Nowak', '987654321', 'Kraków', 25, $educations[1]],
            ['Piotr Zieliński', '555666777', 'Gdańsk', 40, $educations[2]],
        ];
        foreach ($examples as [$name, $phone, $address, $age, $education]) {
            $user = new User();
            $user->setName($name);
            $user->setPhoneNumber($phone);
            $user->setAddress($address);
            $user->setAge($age);
            $user->setEducation($education);
            $this->entityManager->persist($user);
        }
        $this->entityManager->flush();
        return new JsonModel(['status' => 'seeded']);
    }

    public function exportXlsAction()
    {
        $users = $this->entityManager->getRepository(User::class)->findAll();
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->fromArray(['ID', 'Imię i nazwisko', 'Telefon', 'Adres', 'Wiek', 'Wykształcenie'], null, 'A1');
        $row = 2;
        foreach ($users as $user) {
            $sheet->setCellValue('A'.$row, $user->getId());
            $sheet->setCellValue('B'.$row, $user->getName());
            $sheet->setCellValue('C'.$row, $user->getPhoneNumber());
            $sheet->setCellValue('D'.$row, $user->getAddress());
            $sheet->setCellValue('E'.$row, $user->getAge());
            $sheet->setCellValue('F'.$row, $user->getEducation() ? $user->getEducation()->getName() : '');
            $row++;
        }
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $tempFile = tempnam(sys_get_temp_dir(), 'users') . '.xlsx';
        $writer->save($tempFile);
        $response = $this->getResponse();
        $response->getHeaders()->addHeaders([
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => 'attachment; filename="users.xlsx"',
        ]);
        $response->setContent(file_get_contents($tempFile));
        unlink($tempFile);
        return $response;
    }

    public function exportPdfAction()
    {
        $users = $this->entityManager->getRepository(User::class)->findAll();
        $html = '<h1>Raport użytkowników</h1>';
        $html .= '<table border="1" cellpadding="5" cellspacing="0"><tr>';
        $html .= '<th>ID</th><th>Imię i nazwisko</th><th>Telefon</th><th>Adres</th><th>Wiek</th><th>Wykształcenie</th></tr>';
        foreach ($users as $user) {
            $html .= '<tr>';
            $html .= '<td>' . $user->getId() . '</td>';
            $html .= '<td>' . htmlspecialchars($user->getName()) . '</td>';
            $html .= '<td>' . htmlspecialchars($user->getPhoneNumber()) . '</td>';
            $html .= '<td>' . htmlspecialchars($user->getAddress()) . '</td>';
            $html .= '<td>' . $user->getAge() . '</td>';
            $html .= '<td>' . ($user->getEducation() ? htmlspecialchars($user->getEducation()->getName()) : '') . '</td>';
            $html .= '</tr>';
        }
        $html .= '</table>';
        $dompdf = new \Dompdf\Dompdf();
        $dompdf->set_option('isHtml5ParserEnabled', true);
        $dompdf->set_option('isRemoteEnabled', true);
        $dompdf->loadHtml('<style>@page { font-family: DejaVu Sans, sans-serif; }</style>' . $html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();
        $pdfOutput = $dompdf->output();
        $response = $this->getResponse();
        $response->getHeaders()->addHeaders([
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="users.pdf"',
        ]);
        $response->setContent($pdfOutput);
        return $response;
    }
} 