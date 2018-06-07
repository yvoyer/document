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
use Star\Component\Document\Design\Domain\Messaging\Command\AddPropertyConstraint;
use Star\Component\Document\Design\Domain\Messaging\Command\AddPropertyConstraintHandler;
use Star\Component\Document\Design\Domain\Messaging\Command\CreateDocument;
use Star\Component\Document\Design\Domain\Messaging\Command\CreateDocumentHandler;
use Star\Component\Document\Design\Domain\Messaging\Command\CreateProperty;
use Star\Component\Document\Design\Domain\Messaging\Command\CreatePropertyHandler;
use Star\Component\Document\Design\Domain\Model\PropertyDefinition;
use Star\Component\Document\Design\Domain\Model\ReadOnlyDocument;
use Star\Component\Document\Design\Domain\Model\Types;
use Star\Component\Document\Design\Domain\Structure\PropertyExtractor;
use Star\Component\Document\Design\Infrastructure\Persistence\InMemory\DocumentCollection;
use Star\Component\Document\Tools\DocumentBuilder;

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
            new AddPropertyConstraintHandler($this->documents),
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
     * @Given The document :arg1 is created with a bool property named :arg2
     */
    public function theDocumentIsCreatedWithABoolPropertyNamed(string $documentId, string $property)
    {
        $this->iCreateADocumentNamed($documentId);
        $this->iCreateABooleanFieldNamedInDocument($property, $documentId);
    }

    /**
     * @Given The document :arg1 is created with a date property named :arg2
     */
    public function theDocumentIsCreatedWithADatePropertyNamed(string $documentId, string $property)
    {
        $this->iCreateADocumentNamed($documentId);
        $this->iCreateADateFieldNamedInDocument($property, $documentId);
    }

    /**
     * @Given The document :arg1 is created with a number property named :arg2
     */
    public function theDocumentIsCreatedWithANumberPropertyNamed(string $documentId, string $property)
    {
        $this->iCreateADocumentNamed($documentId);
        $this->iCreateANumberFieldNamedInDocument($property, $documentId);
    }

    /**
     * @Given The document :arg1 is created with a custom list property named :arg2 having the options:
     */
    public function theDocumentIsCreatedWithACustomListPropertyNamedHavingTheOptions(
        string $documentId,
        string $property,
        TableNode $table
    ) {
        $this->iCreateADocumentNamed($documentId);
        $this->iCreateACustomListFieldNamedInDocumentWithTheFollowingOptions($property, $documentId, $table);
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
                PropertyDefinition::fromString($property, Types\StringType::class)
            )
        );
    }

    /**
     * @When I create a boolean field named :arg1 in document :arg2
     */
    public function iCreateABooleanFieldNamedInDocument(string $property, string $documentId)
    {
        $this->bus->handleCommand(
            CreateProperty::fromString(
                $documentId,
                PropertyDefinition::fromString($property, Types\BooleanType::class)
            )
        );
    }

    /**
     * @When I create a date field named :arg1 in document :arg2
     */
    public function iCreateADateFieldNamedInDocument(string $property, string $documentId)
    {
        $this->bus->handleCommand(
            CreateProperty::fromString(
                $documentId,
                PropertyDefinition::fromString($property, Types\DateType::class)
            )
        );
    }

    /**
     * @When I create a number field named :arg1 in document :arg2
     */
    public function iCreateANumberFieldNamedInDocument(string $property, string $documentId)
    {
        $this->bus->handleCommand(
            CreateProperty::fromString(
                $documentId,
                PropertyDefinition::fromString($property, Types\NumberType::class)
            )
        );
    }

    /**
     * @When I create a custom list field named :arg1 in document :arg2 with the following options:
     */
    public function iCreateACustomListFieldNamedInDocumentWithTheFollowingOptions(
        string $property,
        string $documentId,
        TableNode $table
    ) {
        $allowed = array_combine(
            array_map(
                function (array $data) {
                    return $data['option-id'];
                },
                $table->getColumnsHash()
            ),
            array_map(
                function (array $data) {
                    return $data['option-value'];
                },
                $table->getColumnsHash()
            )
        );

        $this->bus->handleCommand(
            CreateProperty::fromString(
                $documentId,
                new PropertyDefinition($property, new Types\CustomListType($allowed))
            )
        );
    }

    /**
     * @When I create a single option custom list field named :arg1 in document :arg2 with the following options:
     */
    public function iCreateASingleOptionCustomListFieldNamedInDocumentWithTheFollowingOptions(
        string $property,
        string $documentId,
        TableNode $table
    ) {
        $this->iCreateACustomListFieldNamedInDocumentWithTheFollowingOptions($property, $documentId, $table);
        $this->iMarkThePropertyAsSingleOptionOnTheDocument($property, $documentId);
    }

    /**
     * @When I mark the property :arg1 as required on the document :arg2
     */
    public function iMarkThePropertyAsRequiredOnTheDocument(string $fieldId, string $documentId)
    {
        $this->bus->handleCommand(
            AddPropertyConstraint::fromString(
                $documentId,
                $fieldId,
                'required',
                DocumentBuilder::constraints()->required()
            )
        );
    }

    /**
     * @When I mark the property :arg1 as single option on the document :arg2
     */
    public function iMarkThePropertyAsSingleOptionOnTheDocument(string $fieldId, string $documentId)
    {
        $this->bus->handleCommand(
            AddPropertyConstraint::fromString(
                $documentId,
                $fieldId,
                'single-option',
                DocumentBuilder::constraints()->singleOption()
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
        $rows = [];
        $this->queries->handleQuery(
            $query = GetAllRecordsOfDocument::fromString($documentId)
        )->then(function (array $_r) use (&$rows) {
            $rows = $_r;
        });

        /**
         * @var RecordRow[] $rows
         */
        Assert::assertContainsOnlyInstancesOf(RecordRow::class, $rows);
        $expected = $table->getHash();
        Assert::assertCount(count($expected), $rows);

        foreach ($rows as $key => $row) {
            Assert::assertSame(
                $expected[$key]['record-id'],
                $row->getRecordId()->toString(),
                'Record id not as expected'
            );
            Assert::assertSame(
                $expected[$key]['value'],
                $row->getValue($expected[$key]['property']),
                'Property value not as expected'
            );
        }
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
                ->getPropertyDefinition($name)
                ->hasConstraint('required')
        );
    }

    /**
     * @Then The property :arg1 of document :arg2 should have the following definition:
     */
    public function thePropertyOfDocumentShouldHaveTheFollowingDefinition($property, $documentId, TableNode $table)
    {
        foreach ($table->getHash() as $options) {
            Assert::assertSame(
                $options['type'],
                $this->getDocument($documentId)
                    ->getPropertyDefinition($property)
                    ->getType()->toString()
            );
            Assert::assertTrue(
                $this->getDocument($documentId)
                    ->getPropertyDefinition($property)
                    ->hasConstraint($options['constraint'])
            );
        }
    }
}
