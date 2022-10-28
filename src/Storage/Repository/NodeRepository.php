<?php

declare(strict_types=1);

namespace ITB\CmlifeClient\Storage\Repository;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Exception;
use ITB\CmlifeClient\Exception\StorageException;
use ITB\CmlifeClient\Model\Curriculum\Node;
use ITB\CmlifeClient\Model\Curriculum\NodeInterface;
use ITB\CmlifeClient\Model\Study;
use ITB\CmlifeClient\Storage\Exception\NodeSearchWithTypeAndStudyFailedException;

final class NodeRepository
{
    /** @var EntityRepository<Node> */
    private readonly EntityRepository $entityRepository;

    /**
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(private readonly EntityManagerInterface $entityManager)
    {
        $this->entityRepository = $entityManager->getRepository(Node::class);
    }

    /**
     * @param string $nodeType
     * @param Study $study
     * @return NodeInterface[]
     * @throws NodeSearchWithTypeAndStudyFailedException
     */
    public function findByTypeAndStudy(string $nodeType, Study $study): array
    {
        $queryBuilder = $this->entityRepository->createQueryBuilder('node')
            ->andWhere($this->entityManager->createQueryBuilder()->expr()->isInstanceOf('node', ':type'))
            ->setParameter('type', $nodeType);
        $query = $queryBuilder->getQuery();

        try {
            $nodes = $query->getResult();
        } catch (Exception $exception) {
            throw NodeSearchWithTypeAndStudyFailedException::create($exception);
        }

        return array_values(
            array_filter($nodes, static function (NodeInterface $node) use ($study): bool {
                return $node->belongsToRootNode($study->getCurriculum()->getRootNode());
            })
        );
    }
}
