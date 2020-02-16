<?php declare(strict_types=1);

namespace Star\Component\Document;

use Assert\Assertion;
use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\TableNode;
use PHPUnit\Framework\Assert;
use React\Promise\Deferred;
use React\Promise\PromiseInterface;
use Star\Component\Document\Common\Domain\Messaging\Query;
use Star\Component\Document\Common\Domain\Messaging\QueryBus;
use Star\Component\Document\Common\Domain\Model\DocumentId;
use Star\Component\Document\DataEntry\Domain\Messaging\Command\CreateRecord;
use Star\Component\Document\DataEntry\Domain\Messaging\Command\CreateRecordHandler;
use Star\Component\Document\DataEntry\Domain\Messaging\Command\SetRecordValue;
use Star\Component\Document\DataEntry\Domain\Messaging\Command\SetRecordValueHandler;
use Star\Component\Document\DataEntry\Domain\Messaging\Query\GetAllRecordsOfDocument;
use Star\Component\Document\DataEntry\Domain\Messaging\Query\GetAllRecordsOfDocumentHandler;
use Star\Component\Document\DataEntry\Domain\Messaging\Query\RecordRow;
use Star\Component\Document\DataEntry\Domain\Model\RecordId;
use Star\Component\Document\DataEntry\Domain\Model\Validation\ValidationFailedForProperty;
use Star\Component\Document\DataEntry\Infrastructure\Persistence\InMemory\RecordCollection;
use Star\Component\Document\Design\Builder\DocumentBuilder;
use Star\Component\Document\Design\Domain\Messaging\Command\AddPropertyConstraint;
use Star\Component\Document\Design\Domain\Messaging\Command\AddPropertyConstraintHandler;
use Star\Component\Document\Design\Domain\Messaging\Command\CreateDocument;
use Star\Component\Document\Design\Domain\Messaging\Command\CreateDocumentHandler;
use Star\Component\Document\Design\Domain\Messaging\Command\CreateProperty;
use Star\Component\Document\Design\Domain\Messaging\Command\CreatePropertyHandler;
use Star\Component\Document\Design\Domain\Model\PropertyName;
use Star\Component\Document\Design\Domain\Model\ReadOnlyDocument;
use Star\Component\Document\Design\Domain\Model\Types;
use Star\Component\Document\Design\Domain\Model\Values\ListOptionValue;
use Star\Component\Document\Design\Domain\Model\Values\OptionListValue;
use Star\Component\Document\Design\Domain\Structure\PropertyExtractor;
use Star\Component\Document\Design\Infrastructure\Persistence\InMemory\DocumentCollection;
use Star\Component\DomainEvent\Messaging\CommandBus;
use Star\Component\DomainEvent\Messaging\MessageMapBus;

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
     * @var string[][]
     */
    private $errors = [];

    public function __construct()
    {
        $records = new RecordCollection();
        $this->documents = new DocumentCollection();

        $this->bus = new MessageMapBus();
        $this->bus->registerHandler(CreateDocument::class, new CreateDocumentHandler($this->documents));
        $this->bus->registerHandler(CreateProperty::class, new CreatePropertyHandler($this->documents));
        $this->bus->registerHandler(
            AddPropertyConstraint::class, new AddPropertyConstraintHandler($this->documents)
        );
        $this->bus->registerHandler(
            SetRecordValue::class,
            new SetRecordValueHandler($records)
        );
        $this->bus->registerHandler(
            CreateRecord::class,
            new CreateRecordHandler($records, $this->documents, $this->documents)
        );

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
        return $this->documents->getDocumentByIdentity(DocumentId::fromString($documentId));
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
     * @Given The property :arg1 in document :arg2 is configured with format :arg3
     */
#    public function thePropertyInDocumentIsConfiguredWithFormat($property, $documentId, $format)
 #   {
  #      $this->iCreateADocumentNamed($documentId);
   #     $this->iCreateADateFieldNamedInDocument($property, $documentId);
    #}

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
        $this->bus->dispatchCommand(new CreateDocument($id = DocumentId::fromString($documentId)));
    }

    /**
     * @When I create a text field named :arg1 in document :arg2
     */
    public function iCreateATextFieldNamedInDocument(string $property, string $documentId)
    {
        $this->bus->dispatchCommand(
            new CreateProperty(
                DocumentId::fromString($documentId),
                PropertyName::fromString($property),
                new Types\StringType()
            )
        );
    }

    /**
     * @When I create a boolean field named :arg1 in document :arg2
     */
    public function iCreateABooleanFieldNamedInDocument(string $property, string $documentId)
    {
        $this->bus->dispatchCommand(
            new CreateProperty(
                DocumentId::fromString($documentId),
                PropertyName::fromString($property),
                new Types\BooleanType()
            )
        );
    }

    /**
     * @When I create a date field named :arg1 in document :arg2
     */
    public function iCreateADateFieldNamedInDocument(string $property, string $documentId)
    {
        $this->bus->dispatchCommand(
            new CreateProperty(
                DocumentId::fromString($documentId),
                PropertyName::fromString($property),
                new Types\DateType()
            )
        );
    }

    /**
     * @When I create a number field named :arg1 in document :arg2
     */
    public function iCreateANumberFieldNamedInDocument(string $property, string $documentId)
    {
        $this->bus->dispatchCommand(
            new CreateProperty(
                DocumentId::fromString($documentId),
                PropertyName::fromString($property),
                new Types\NumberType()
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

        $this->bus->dispatchCommand(
            new CreateProperty(
                DocumentId::fromString($documentId),
                PropertyName::fromString($property),
                new Types\CustomListType(
                    'custom-list',
                    OptionListValue::fromArray(
                        \array_map(
                            function (int $key) use ($allowed) {
                                return ListOptionValue::withValueAsLabel($key, $allowed[$key]);
                            },
                            \array_keys($allowed)
                        )
                    )
                )
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
        $this->iMarkThePropertyAsRequiredOnTheDocument($property, $documentId);
    }

    /**
     * @When I mark the property :arg1 of document :arg2 with constraints:
     */
    public function iMarkThePropertyOfDocumentWithConstraints(string $property, string $document, TableNode $table)
    {
        $rows = $table->getHash();
        Assert::assertGreaterThan(0, \count($rows));
        $documentId = DocumentId::fromString($document);
        $propertyName = PropertyName::fromString($property);
        foreach($rows as $row) {
            $name = $row['name'];
            $value = \explode(';', $row['value']);

            $this->bus->dispatchCommand(
                new AddPropertyConstraint(
                    $documentId,
                    $propertyName,
                    DocumentBuilder::constraints()->fromString($name, $value)
                )
            );
        }
    }

    /**
     * @When I mark the property :arg1 as required on the document :arg2
     */
    public function iMarkThePropertyAsRequiredOnTheDocument(string $fieldId, string $documentId)
    {
        $this->bus->dispatchCommand(
            new AddPropertyConstraint(
                DocumentId::fromString($documentId),
                PropertyName::fromString($fieldId),
                DocumentBuilder::constraints()->required()
            )
        );
    }

    /**
     * @When I mark the property :arg1 as requiring at least :arg2 options on the document :arg3
     */
    public function iMarkThePropertyAsRequiringAtLeastOptionsOnTheDocument(string $fieldId, string $count, string $documentId)
    {
        $this->bus->dispatchCommand(
            new AddPropertyConstraint(
                DocumentId::fromString($documentId),
                PropertyName::fromString($fieldId),
                DocumentBuilder::constraints()->requiresOptionCount((int) $count)
            )
        );
    }

    /**
     * @When I enter the following values to document :arg1
     */
    public function iEnterTheFollowingValuesToDocument(string $documentId, TableNode $table)
    {
        foreach ($table->getHash() as $data) {
            $recordId = RecordId::fromString($data['record-id']);
            $property = $data['property'];
            $value = $data['value'];

            try {
                $this->bus->dispatchCommand(
                    new CreateRecord(
                        DocumentId::fromString($documentId),
                        $recordId,
                        [$property => $value]
                    )
                );
            } catch (ValidationFailedForProperty $exception) {
                $propertyErrors = $exception->getErrors()->getErrorsForProperty($property, 'en');
                Assert::assertCount(1, $propertyErrors);
                $this->errors[$recordId->toString()][$property] = $propertyErrors[0];
            }
        }
    }

    /**
     * @Then The records list of document :arg1 should looks like:
     */
    public function theRecordsListOfDocumentShouldLooksLike(string $documentId, TableNode $table)
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
            $property = $expected[$key]['property'];
            $recordId = $expected[$key]['record-id'];
            $expectedValue = $expected[$key]['value'];

            Assert::assertSame($recordId, $row->getRecordId()->toString(), 'Record id not as expected');
            Assert::assertSame(
                $expectedValue,
                $row->getValue($property),
                \sprintf('Value of property "%s" is not as expected', $property)
            );
        }
    }

    /**
     * @Then The document :arg1 should have no properties
     */
    public function theDocumentShouldHaveNoProperties(string $documentId)
    {
        $this->getDocument($documentId)->acceptDocumentVisitor($visitor = new PropertyExtractor());
        Assert::assertCount(0, $visitor);
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
                ->getPropertyDefinition(PropertyName::fromString($name))
                ->hasConstraint('required')
        );
    }

    /**
     * @Then The property :arg1 of document :arg2 should have the following definition:
     */
    public function thePropertyOfDocumentShouldHaveTheFollowingDefinition($property, $documentId, TableNode $table)
    {
        $definition = $this->getDocument($documentId)->getPropertyDefinition(PropertyName::fromString($property));
        var_dump($definition);
        foreach ($table->getHash() as $options) {
            Assert::assertSame(
                $options['type'],
                $definition
                    ->getType()->toString()
            );
            Assert::assertTrue(
                $definition
                    ->hasConstraint($options['constraint'])
            );
        }
    }

    /**
     * @Then The record entry should have failed:
     */
    public function theRecordsOfDocumentShouldHaveFailed(TableNode $table)
    {
        foreach ($table as $row) {
            $recordId = $row['record-id'];
            $property = $row['property'];
            Assert::assertArrayHasKey($recordId, $this->errors);
            $recordErrors = $this->errors[$recordId];
            Assert::assertArrayHasKey($property, $recordErrors);
            $propertyErrors = $recordErrors[$property];
            Assert::assertSame($row['message'], $propertyErrors);
        }
    }
}
