<?php declare(strict_types=1);

namespace App\Controller;

use App\Authentication\AuthenticationContext;
use App\Twig\Design\TwigDocumentCollection;
use Star\Component\Document\Design\Domain\Messaging\Query\FindAllMyDocuments;
use Star\Component\DomainEvent\Messaging\QueryBus;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class Dashboard extends AppController
{
    /**
     * @Route(path="/", name="dashboard")
     *
     * @param AuthenticationContext $context
     * @param QueryBus $queries
     * @return Response
     */
    public function __invoke(AuthenticationContext $context, QueryBus $queries, Request $request): Response {
        $queries->dispatchQuery($query = new FindAllMyDocuments($context->getLoggedMember(), $request->getLocale()));
        return $this->render(
            'Dashboard\index.html.twig',
            [
                'documents' => TwigDocumentCollection::fromReadOnlyDocuments(...$query->getResult()),
            ]
        );
    }
}
