<?php

namespace Star\Component\Document;

use Assert\Assertion;
use Behat\Behat\Context\Context;
use Behat\Behat\Tester\Exception\PendingException;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use PHPUnit\Framework\Assert;
use React\Promise\Deferred;
use React\Promise\PromiseInterface;
use Star\Component\Document\Application\Port\DocumentDesignerToSchema;
use Star\Component\Document\Common\Domain\Messaging\Command;
use Star\Component\Document\Common\Domain\Messaging\CommandBus;
use Star\Component\Document\Common\Domain\Messaging\Query;
use Star\Component\Document\Common\Domain\Messaging\QueryBus;
use Star\Component\Document\Common\Domain\Model\DocumentId;
use Star\Component\Document\DataEntry\Domain\Messaging\Command\SetRecordValue;
use Star\Component\Document\DataEntry\Domain\Messaging\Command\SetRecordValueHandler;
use Star\Component\Document\DataEntry\Domain\Messaging\Query\GetAllRecordsOfDocument;
use Star\Component\Document\DataEntry\Domain\Messaging\Query\GetAllRecordsOfDocumentHandler;
use Star\Component\Document\DataEntry\Domain\Messaging\Query\RecordRow;
use Star\Component\Document\DataEntry\Infrastructure\Persistence\InMemory\RecordCollection;
use Star\Component\Document\Design\Domain\Messaging\Command\ChangePropertyDefinition;
use Star\Component\Document\Design\Domain\Messaging\Command\ChangePropertyDefinitionHandler;
use Star\Component\Document\Design\Domain\Messaging\Command\CreateDocument;
use Star\Component\Document\Design\Domain\Messaging\Command\CreateDocumentHandler;
use Star\Component\Document\Design\Domain\Messaging\Command\CreateProperty;
use Star\Component\Document\Design\Domain\Messaging\Command\CreatePropertyHandler;
use Star\Component\Document\Design\Domain\Model\PropertyDefinition;
use Star\Component\Document\Design\Domain\Model\PropertyName;
use Star\Component\Document\Design\Domain\Model\ReadOnlyDocument;
use Star\Component\Document\Design\Domain\Model\Tools\AttributeBuilder;
use Star\Component\Document\Design\Domain\Structure\PropertyExtractor;
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
     * @var QueryBus
     */
    private $queries;

    /**
     * Initializes context.
     *
     * Every scenario gets its own context instance.
     * You can also pass arbitrary arguments to the
     * context constructor through behat.yml.
     */
    public function __construct()
    {
        $records = new RecordCollection();
        $this->documents = new DocumentCollection();
        $handlers = [
            new CreateDocumentHandler($this->documents),
            new CreatePropertyHandler($this->documents),
            new ChangePropertyDefinitionHandler($this->documents),
            new SetRecordValueHandler($records, new DocumentDesignerToSchema($this->documents)),
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

        $queries = [
            new GetAllRecordsOfDocumentHandler($records),
        ];
        $this->queries = new class($queries) implements QueryBus {
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

            /**
             * @param Query $query
             *
             * @return PromiseInterface
             */
            public function handleQuery(Query $query): PromiseInterface
            {
                $class = get_class($query);
                if (! isset($this->handlers[$class])) {
                    throw new \RuntimeException('Handler for class ' . get_class($query) . ' is not implemented yet.');
                }

                $handler = $this->handlers[$class];
                Assertion::true(
                    is_callable($handler),
                    sprintf('Command handler "%s" must be invokable.', $class)
                );
                $handler($query, $deferred = new Deferred());

                return $deferred->promise();
            }
        };
    }

    private function getDocument(string $documentId): ReadOnlyDocument
    {
        return $this->documents->getDocumentByIdentity(new DocumentId($documentId));
    }

    /**
     * @Given The document :arg1 is created without any properties
     */
    public function theDocumentIsCreatedWithoutAnyProperties(string $documentId)
    {
        $this->iCreateADocumentNamed($documentId);
    }

    /**
     * @Given The document :arg1 is created with a text property named :arg2
     */
    public function theDocumentIsCreatedWithATextPropertyNamed(string $documentId, string $property)
    {
        $this->iCreateADocumentNamed($documentId);
        $this->iCreateATextFieldNamedInDocument($property, $documentId);
    }

    /**
     * @When I create a document named :arg1
     */
    public function iCreateADocumentNamed(string $documentId)
    {
        $this->bus->handleCommand(CreateDocument::fromString($documentId));
    }

    /**
     * @When I create a text field named :arg1 in document :arg2
     */
    public function iCreateATextFieldNamedInDocument(string $property, string $documentId)
    {
        $this->bus->handleCommand(
            CreateProperty::fromString(
                $documentId,
                PropertyDefinition::textDefinition($property)
            )
        );
    }

    /**
     * @When I mark the property :arg1 as required on the document :arg2
     */
    public function iMarkThePropertyAsRequiredOnTheDocument(string $fieldId, string $documentId)
    {
        $this->bus->handleCommand(
            ChangePropertyDefinition::fromString(
                $documentId,
                $fieldId,
                AttributeBuilder::create()->required()
            )
        );
    }

    /**
     * @When I enter the following values to document :arg1
     */
    public function iEnterTheFollowingValuesToDocument(string $documentId, TableNode $table)
    {
        foreach ($table->getHash() as $data) {
            $this->bus->handleCommand(
                SetRecordValue::fromString(
                    $documentId,
                    $data['record-id'],
                    $data['property'],
                    $data['value']
                )
            );
        }
    }

    /**
     * @Then The records list of document the :arg1 should looks like:
     */
    public function theRecordsListOfDocumentTheShouldLooksLike(string $documentId, TableNode $table)
    {
        $result = [];
        $this->queries->handleQuery(
            $query = GetAllRecordsOfDocument::fromString($documentId)
        )->then(function (array $_r) use (&$result) {
            $result = $_r;
        });

        /**
         * @var RecordRow[] $result
         */
        Assert::assertContainsOnlyInstancesOf(RecordRow::class, $result);
        foreach ($table->getHash() as $data) {
            // todo
            Assert::assertSame($data['record-id'], $result->getRecordId()->toString());
            Assert::assertSame($data['value'], $result->getValue($data['property']));
        }
        throw new PendingException();
    }

    /**
     * @Then The document :arg1 should have no properties
     */
    public function theDocumentShouldHaveNoProperties(string $documentId)
    {
        $this->getDocument($documentId)->acceptDocumentVisitor($visitor = new PropertyExtractor());
        Assert::assertCount(0, $visitor->properties());
    }

    /**
     * @Then The document :arg1 should have a property :arg2
     */
    public function theDocumentShouldHaveAProperty(string $documentId, string $name)
    {
        $this->getDocument($documentId)->acceptDocumentVisitor($visitor = new PropertyExtractor());
        Assert::assertTrue($visitor->hasProperty($name));
    }

    /**
     * @Then The document :arg1 should have a required property :arg2
     */
    public function theDocumentShouldHaveARequiredProperty(string $documentId, string $name)
    {
        Assert::assertTrue(
            $this->getDocument($documentId)
                ->getPropertyDefinition(new PropertyName($name))
                ->isRequired()
        );
    }
}
