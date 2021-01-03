<?php declare(strict_types=1);

namespace App\Controller\Document;

use App\Controller\AppController;
use Star\Component\Document\Design\Domain\Messaging\Command\CreateDocument;
use Star\Component\Document\Design\Domain\Model\DocumentId;
use Star\Component\DomainEvent\Messaging\CommandBus;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class NewDocument extends AppController
{
    /**
     * @Route(name="document_new", path="/documents", methods={"POST"})
     *
     * @param CommandBus $bus
     * @return Response
     */
    public function __invoke(CommandBus $bus): Response
    {
        $command = new CreateDocument($id = DocumentId::random());
        $bus->dispatchCommand($command);

        return $this->redirect($this->generateUrl('document_design', ['id' => $id->toString()]));
    }
}
