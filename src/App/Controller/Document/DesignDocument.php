<?php declare(strict_types=1);

namespace App\Controller\Document;

use App\Controller\AppController;
use Star\Component\Document\Design\Infrastructure\Templating\SymfonyForm\DocumentDesignType;
use Star\Component\DomainEvent\Messaging\QueryBus;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class DesignDocument extends AppController
{
    /**
     * @var QueryBus
     */
    private $queries;

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

        return $this->render(
            'Design/design.html.twig',
            [
                'document' => ['name' => 'TODO name'],
                'form' => $form->createView(),
            ]
        );
    }
}
