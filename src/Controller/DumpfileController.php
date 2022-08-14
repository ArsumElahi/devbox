<?php

namespace App\Controller;

use App\Entity\DumpFile;
use App\Entity\FileMetadata;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


/**
 * @Route("/api", name="api_")
 */
class DumpfileController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param array $data
     * @return void
     */
    public function createLog(array $data)
    {
        $file_data = new DumpFile();

        $file_data->setServiceName($data['services_name']);
        $file_data->setCodeType($data['response_code']);
        $file_data->setDateTime($data['timestamp']);
        $file_data->setMethodType($data['route_details']);

        $em = $this->getDoctrine()->getManager();
        $em->persist($file_data);
        $em->flush();
 
        dump('Save log with id => ' . $file_data->getId());
    }

    /**
     * @Route("/count", name="count", methods={"GET"})
     */
    public function count(Request $request): object | array 
    {
        $data = json_decode($request->getContent(), true);

        $services_name = $data['serviceNames'] ?? null;
        $status_code = $data['statusCode'] ?? null;
        $start_date = $data['startDate'] ?? null;

        
        
        $qb = $this->entityManager->createQueryBuilder();
        $qb->select('df')
            ->from(DumpFile::class, 'df');

        if (!empty($services_name)){
            $qb->where('df.service_name IN (:services_name)')
                ->setParameter('services_name', $services_name);
        }

        if (!empty($status_code))
            $qb->orWhere($qb->expr()->like('df.status_code', $qb->expr()->literal('%' . $status_code . '%')));

        if(!empty($start_date))
            $qb->orWhere($qb->expr()->like('df.start_date', $qb->expr()->literal(date("Y-m-d h:i:s", strtotime($start_date)))));
       
        return $this->json("Counter: " . count($qb->getQuery()->getResult()));
    }

    /**
     * @param string $file_name
     * @return int|null
     */
    public function checkFileStatus(string $file_name): ?int
    {
        $file_name = $this->entityManager->getRepository(FileMetadata::class)->findBy(array('file_name' => $file_name));
        return (isset($file_name[0]->error_line_no)) ? $file_name[0]->error_line_no : null;
    }

    /**
     * @param $file_path
     * @param $total_record
     * @param $error_line
     * @return bool
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function createORUpdateErrorLog($file_path, $total_record, $error_line): bool
    {
        $file_metadata = $this->checkFileStatus($file_path);

        if (!$file_metadata) {
            $file_metadata = new FileMetadata();
            $file_metadata->setFileName($file_path);
            $file_metadata->setTotalRows($total_record);
            $file_metadata->setErrorLineNo($error_line);
            $em = $this->getDoctrine()->getManager();
            $em->persist($file_metadata);
            $em->flush();
        } else {
            $this->entityManager->createQueryBuilder()
                ->update(FileMetadata::class, 'file_metadata')
                ->set('file_metadata.error_line_no', ':error_line')
                ->setParameter('error_line', $error_line)
                ->where('file_metadata.file_name = :file_name')
                ->setParameter('file_name', $file_path)
                ->getQuery()
                ->getSingleScalarResult();
        }

        return 0;
    }

    public function purgeError($file_path)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $single_user = $this->getDoctrine()->getRepository(FileMetadata::class)->findOneBy(['file_name' => $file_path]);
        $entityManager->remove($single_user);
        $entityManager->flush();
    }
}
