<?php

declare(strict_types=1);

namespace ITB\CmlifeClient\Model;

use Doctrine\ORM\Mapping as ORM;
use ITB\CmlifeClient\Model\Curriculum\Node;
use ITB\CmlifeClient\Model\Curriculum\NodeFactory;
use LogicException;

#[ORM\Entity]
final class Curriculum
{
    /**
     * @param int $id
     * @param string $uri
     * @param string|null $studyPlanUrl
     * @param string $curriculumDocumentUrl
     * @param Node\RootNode $rootNode
     */
    public function __construct(
        #[ORM\Id]
        #[ORM\Column(name: 'id', type: 'integer')]
        private readonly int $id,
        #[ORM\Column(name: 'uri', type: 'string')]
        private readonly string $uri,
        #[ORM\Column(name: 'study_plan_url', type: 'string', nullable: true)]
        private readonly ?string $studyPlanUrl,
        #[ORM\Column(name: 'curriculum_document_url', type: 'string')]
        private readonly string $curriculumDocumentUrl,
        #[ORM\ManyToOne(targetEntity: Node::class, cascade: ['persist'])]
        #[ORM\JoinColumn(name: 'root_node_id', referencedColumnName: 'id')]
        private readonly Node\RootNode $rootNode
    ) {
    }

    /**
     * @param array<string, mixed> $curriculumData
     * @return Curriculum
     */
    public static function create(array $curriculumData): Curriculum
    {
        $id = $curriculumData['id'];
        $uri = $curriculumData['uri'];
        $studyPlanUrl = $curriculumData['studyPlanUrl'] ?? null;
        $curriculumDocumentUrl = $curriculumData['curriculumDocumentUrl'];

        $rootNode = NodeFactory::createNode($curriculumData['root']);
        if (!$rootNode instanceof Node\RootNode) {
            throw new LogicException(
                sprintf('The root of the curriculums node tree should always be a root node, but it is a %s', get_debug_type($rootNode))
            );
        }

        return new self($id, $uri, $studyPlanUrl, $curriculumDocumentUrl, $rootNode);
    }

    /**
     * @return string
     */
    public function getCurriculumDocumentUrl(): string
    {
        return $this->curriculumDocumentUrl;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return Node\RootNode
     */
    public function getRootNode(): Node\RootNode
    {
        return $this->rootNode;
    }

    /**
     * @return string|null
     */
    public function getStudyPlanUrl(): ?string
    {
        return $this->studyPlanUrl;
    }

    /**
     * @return string
     */
    public function getUri(): string
    {
        return $this->uri;
    }
}
