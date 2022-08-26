<?php declare(strict_types=1);

namespace App\Controller\DocumentType;

use App\Controller\AppController;
use Star\Component\Document\Design\Domain\Messaging\Query\FindSchemaForDocumentTypes;
use Star\Component\Document\Design\Domain\Model\DocumentTypeId;
use Star\Component\Document\Design\Infrastructure\Templating\SymfonyForm\DocumentDesignType;
use Star\Component\DomainEvent\Messaging\QueryBus;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use function iterator_to_array;
use function var_dump;

final class DocumentTypeDesign extends AppController
{
    /**
     * @Route(name="document_type_design", path="/document-types/{id}", methods={"GET", "PUT"})
     *
     * @param string $id
     * @param QueryBus $bus
     * @param Request $request
     * @return Response
     */
    public function __invoke(string $id, QueryBus $bus, Request $request): Response
    {
        $typeId = DocumentTypeId::fromString($id);
        $bus->dispatchQuery($query = new FindSchemaForDocumentTypes($request->getLocale(), $typeId));
        var_dump($schema = $query->getSingleSchema($typeId));

        $supportedLocales = [ // todo make configurable using system setting
            'en'
        ];
        $data = [];
        foreach ($supportedLocales as $locale) {
            $data[$locale . '_name'] = $schema->getName($locale);
        }

        $form = $this->createForm(
            DocumentDesignType::class,
            [

                'name' => $schema->getName(),

            ]
        );

        return $this->render(
            'DocumentType/design.html.twig',
            [
                'document_type' => $query->getSingleSchema($typeId),
                'form' => $form->createView(),
            ]
        );
    }
}
