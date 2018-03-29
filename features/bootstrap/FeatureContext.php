<?php

namespace Star\Component\Document;

use Assert\Assertion;
use Behat\Behat\Context\Context;
use Behat\Behat\Tester\Exception\PendingException;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use PHPUnit\Framework\Assert;
use Star\Component\Document\Common\Domain\Messaging\Command;
use Star\Component\Document\Common\Domain\Messaging\CommandBus;
use Star\Component\Document\Common\Domain\Model\DocumentId;
use Star\Component\Document\Design\Domain\Messaging\Command\CreateDocument;
use Star\Component\Document\Design\Domain\Messaging\Command\CreateDocumentHandler;
use Star\Component\Document\Design\Domain\Messaging\Command\CreateProperty;
use Star\Component\Document\Design\Domain\Messaging\Command\CreatePropertyHandler;
use Star\Component\Document\Design\Domain\Model\DocumentDesignerAggregate;
use Star\Component\Document\Design\Domain\Model\NullableValue;
use Star\Component\Document\Design\Domain\Model\PropertyName;
use Star\Component\Document\Design\Domain\Model\Structure\ReadDocumentStructure;
use Star\Component\Document\Design\Infrastructure\Persistence\InMemory\DocumentCollection;

/**
 * Defines application features from the specific context.
 */
class FeatureContext implements Context
{
    /**
     * @var DocumentCollection
     */
    private $documents;

    /**
     * @var CommandBus
     */
    private $bus;

    /**
     * Initializes context.
     *
     * Every scenario gets its own context instance.
     * You can also pass arbitrary arguments to the
     * context constructor through behat.yml.
     */
    public function __construct()
    {
        $this->documents = new DocumentCollection();
        $handlers = [
            new CreateDocumentHandler($this->documents),
            new CreatePropertyHandler($this->documents),
        ];

        $this->bus = new class($handlers) implements CommandBus {
            /**
             * @var callable[]
             */
            private $handlers;

            /**
             * @param callable[] $handlers
             */
            public function __construct(array $handlers)
            {
                array_map(
                    function ($handler) {
                        $command = str_replace('Handler', '', get_class($handler));
                        $this->handlers[$command] = $handler;
                    },
                    $handlers
                );
            }

            public function handleCommand(Command $command)
            {
                $class = get_class($command);
                if (! isset($this->handlers[$class])) {
                    throw new \RuntimeException('Handler for class ' . get_class($command) . ' is not implemented yet.');
                }

                $handler = $this->handlers[$class];
                Assertion::true(
                    is_callable($handler),
                    sprintf('Command handler "%s" must be invokable.', $class)
                );
                $handler($command);
            }
        };
    }

    /**
     * @Given The document :arg1 is created
     */
    public function theDocumentIsCreated(string $documentId)
    {
        $this->bus->handleCommand(new CreateDocument(new DocumentId($documentId)));
    }

    /**
     * @Given The document :arg1 has a section named :arg2
     */
    public function theDocumentHasASectionNamed(string $documentId, string $sectionName)
    {
        $this->bus->handleCommand(
            new CreateProperty(
                new DocumentId($documentId),
                new PropertyName($sectionName),
                new NullableValue()
            )
        );
    }

    /**
     * @Given The document :arg1 as a text field named :arg2 under the section :arg3
     */
    public function theDocumentAsATextFieldNamedUnderTheSection(string $documentId, string $fieldId, string $sectionName)
    {
        throw new PendingException();
    }

    /**
     * @When I update the property (required) of the field :arg1 on the document :arg2
     */
    public function iUpdateThePropertyOfTheFieldOnTheDocument(string $fieldId, string $documentId)
    {
        throw new PendingException();
    }

    /**
     * @When I create a text field named :arg1 under the section :arg2 of document :arg3
     */
    public function iCreateATextFieldNamedUnderTheSectionOfDocument(string $fieldId, string $sectionName, string $documentId)
    {
        throw new PendingException();
    }

    /**
     * @When I create a document named :arg1
     */
    public function iCreateADocumentNamed(string $documentId)
    {
        $this->theDocumentIsCreated($documentId);
    }

    /**
     * @When I create a section named :arg1 on the document :arg2
     */
    public function iCreateASectionNamedOnTheDocument(string $sectionName, string $documentId)
    {
        $this->theDocumentHasASectionNamed($sectionName, $documentId);
    }

    /**
     * @Then The structure of the document :arg1 should look like:
     */
    public function theStructureOfTheDocumentShouldLookLike(string $documentId, PyStringNode $string)
    {
        /**
         * @var $document DocumentDesignerAggregate
         */
        $document = $this->documents->getDocumentByIdentity(new DocumentId($documentId));
        $document->acceptDocumentVisitor($visitor = new ReadDocumentStructure());
        Assert::assertSame($string->getRaw(), $visitor->toString());
    }
}
