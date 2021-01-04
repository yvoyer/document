<?php declare(strict_types=1);

namespace App\Controller;

use Star\Component\Document\Design\Domain\Messaging\Query\FindAllMyDocuments;
use Star\Component\DomainEvent\Messaging\QueryBus;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class Dashboard extends AppController
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
     * @Route(path="/", name="dashboard")
     *
     * @return Response
     */
    public function __invoke(): Response {
        $this->queries->dispatchQuery($query = new FindAllMyDocuments());
\var_dump($query->getResult());
        return $this->render(
            'Dashboard\index.html.twig',
            [
                'documents' => $query->getResult(),
            ]
        );
    }
}
