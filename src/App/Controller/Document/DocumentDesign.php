<?php declare(strict_types=1);

namespace App\Controller\Document;

use App\Controller\AppController;
use Star\Component\Document\Design\Domain\Messaging\Query\FindSchemaForDocuments;
use Star\Component\Document\Design\Domain\Model\DocumentId;
use Star\Component\Document\Design\Infrastructure\Templating\SymfonyForm\DocumentDesignType;
use Star\Component\DomainEvent\Messaging\QueryBus;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class DocumentDesign extends AppController
{
    /**
     * @var QueryBus
     */
    private $queries;

    public function __construct(QueryBus $queries)
    {
        $this->queries = $queries;
    }

    /**
     * @Route(name="document_design", path="/documents/{id}", methods={"GET", "PUT"})
     *
     * @param string $id
     *
     * @return Response
     */
    public function __invoke(string $id): Response
    {
        $form = $this->createForm(DocumentDesignType::class);
        $this->queries->dispatchQuery($query = new FindSchemaForDocuments(DocumentId::fromString($id)));

        \var_dump($query->getResult());
        return $this->render(
            'Document/design.html.twig',
            [
                'document' => ['name' => 'TODO name'],
                'form' => $form->createView(),
            ]
        );
    }
}
