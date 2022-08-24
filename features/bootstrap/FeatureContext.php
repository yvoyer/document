<?php declare(strict_types=1);

namespace Star\Component\Document\Tests;

use Assert\Assertion;
use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\TableNode;
use DateTimeImmutable;
use PHPUnit\Framework\Assert;
use Star\Component\Document\DataEntry\Domain\Messaging\Command\CreateRecord;
use Star\Component\Document\DataEntry\Domain\Messaging\Command\CreateRecordHandler;
use Star\Component\Document\DataEntry\Domain\Messaging\Command\SetRecordValue;
use Star\Component\Document\DataEntry\Domain\Messaging\Command\SetRecordValueHandler;
use Star\Component\Document\DataEntry\Domain\Messaging\Query\GetAllRecordsOfDocument;
use Star\Component\Document\DataEntry\Domain\Messaging\Query\GetAllRecordsOfDocumentHandler;
use Star\Component\Document\DataEntry\Domain\Messaging\Query\RecordRow;
use Star\Component\Document\DataEntry\Domain\Model\DocumentId;
use Star\Component\Document\DataEntry\Domain\Model\Validation\ValidationFailedForProperty;
use Star\Component\Document\DataEntry\Domain\Model\Values\ListOptionValue;
use Star\Component\Document\DataEntry\Domain\Model\Values\OptionListValue;
use Star\Component\Document\DataEntry\Domain\Model\Values\RecordValueGuesser;
use Star\Component\Document\DataEntry\Infrastructure\Persistence\InMemory\RecordCollection;
use Star\Component\Document\Design\Builder\DocumentTypeBuilder;
use Star\Component\Document\Design\Domain\Messaging\Command\AddPropertyConstraint;
use Star\Component\Document\Design\Domain\Messaging\Command\AddPropertyConstraintHandler;
use Star\Component\Document\Design\Domain\Messaging\Command\AddPropertyParameter;
use Star\Component\Document\Design\Domain\Messaging\Command\AddPropertyParameterHandler;
use Star\Component\Document\Design\Domain\Messaging\Command\CreateDocumentType;
use Star\Component\Document\Design\Domain\Messaging\Command\CreateDocumentTypeHandler;
use Star\Component\Document\Design\Domain\Messaging\Command\CreateProperty;
use Star\Component\Document\Design\Domain\Messaging\Command\CreatePropertyHandler;
use Star\Component\Document\Design\Domain\Model\Constraints\AfterDate;
use Star\Component\Document\Design\Domain\Model\Constraints\BeforeDate;
use Star\Component\Document\Design\Domain\Model\Constraints\BetweenDate;
use Star\Component\Document\Design\Domain\Model\Constraints\MaximumLength;
use Star\Component\Document\Design\Domain\Model\Constraints\MinimumLength;
use Star\Component\Document\Design\Domain\Model\Constraints\Regex;
use Star\Component\Document\Design\Domain\Model\Constraints\RequiresOptionCount;
use Star\Component\Document\Design\Domain\Model\Constraints\RequiresValue;
use Star\Component\Document\Design\Domain\Model\DocumentTypeAggregate;
use Star\Component\Document\Design\Domain\Model\DocumentTypeId;
use Star\Component\Document\Design\Domain\Model\DocumentName;
use Star\Component\Document\Design\Domain\Model\Parameters\DateFormat;
use Star\Component\Document\Design\Domain\Model\Parameters\DefaultValue;
use Star\Component\Document\Design\Domain\Model\Parameters\ParameterData;
use Star\Component\Document\Design\Domain\Model\PropertyName;
use Star\Component\Document\Design\Domain\Model\Test\NullOwner;
use Star\Component\Document\Design\Domain\Model\Types;
use Star\Component\Document\Design\Domain\Structure\PropertyExtractor;
use Star\Component\Document\Design\Infrastructure\Persistence\InMemory\ConstraintFactory;
use Star\Component\Document\Design\Infrastructure\Persistence\InMemory\DocumentTypeCollection;
use Star\Component\DomainEvent\Messaging\CommandBus;
use Star\Component\DomainEvent\Messaging\MessageMapBus;
use function array_keys;
use function array_map;
use function count;
use function json_decode;
use function sprintf;

/**
 * Defines application features from the specific context.
 */
class FeatureContext implements Context
{
    /**
     * @var DocumentTypeCollection
     */
    private $documents;

    /**
     * @var CommandBus
     */
    private $bus;

    /**
     * @var MessageMapBus
     */
    private $queries;

    /**
     * @var string[][]
     */
    private $errors = [];

    public function __construct()
    {
        $records = new RecordCollection();
        $this->documents = new DocumentTypeCollection();
        $constraints = new ConstraintFactory(
            [
                'required' => RequiresValue::class,
                'single-option' => RequiresOptionCount::class,
                'required-count' => RequiresOptionCount::class,
                'between-date' => BetweenDate::class,
                'after-date' => AfterDate::class,
                'before-date' => BeforeDate::class,
                'minimum-length' => MinimumLength::class,
                'maximum-length' => MaximumLength::class,
                'regex' => Regex::class,
            ]
        );

        $this->bus = new MessageMapBus();
        $this->bus->registerHandler(CreateDocumentType::class, new CreateDocumentTypeHandler($this->documents));
        $this->bus->registerHandler(CreateProperty::class, new CreatePropertyHandler($this->documents));
        $this->bus->registerHandler(
            AddPropertyConstraint::class,
            new AddPropertyConstraintHandler($this->documents, $constraints)
        );
        $this->bus->registerHandler(
            AddPropertyParameter::class,
            new AddPropertyParameterHandler($this->documents)
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
        $this->queries = new MessageMapBus();
        array_map(
            function(callable $handler) {
                $command = str_replace('Handler', '', get_class($handler));
                $this->queries->registerHandler($command, $handler);
            },
            $queries
        );
    }

    private function getDocument(string $documentId): DocumentTypeAggregate
    {
        return $this->documents->getDocumentByIdentity(DocumentTypeId::fromString($documentId));
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
        $this->bus->dispatchCommand(
            new CreateDocumentType(
                $id = DocumentTypeId::fromString($documentId),
                new DocumentName($documentId),
                new NullOwner()
            )
        );
    }

    /**
     * @When I create a text field named :arg1 in document :arg2
     */
    public function iCreateATextFieldNamedInDocument(string $property, string $documentId)
    {
        $this->bus->dispatchCommand(
            new CreateProperty(
                DocumentTypeId::fromString($documentId),
                PropertyName::fromLocalizedString($property, 'en'),
                new Types\StringType(),
                new NullOwner(),
                new DateTimeImmutable()
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
                DocumentTypeId::fromString($documentId),
                PropertyName::fromLocalizedString($property, 'en'),
                new Types\BooleanType(),
                new NullOwner(),
                new DateTimeImmutable()
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
                DocumentTypeId::fromString($documentId),
                PropertyName::fromLocalizedString($property, 'en'),
                new Types\DateType(),
                new NullOwner(),
                new DateTimeImmutable()
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
                DocumentTypeId::fromString($documentId),
                PropertyName::fromLocalizedString($property, 'en'),
                new Types\NumberType(),
                new NullOwner(),
                new DateTimeImmutable()
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
                DocumentTypeId::fromString($documentId),
                PropertyName::fromLocalizedString($property, 'en'),
                new Types\ListOfOptionsType(
                    'custom-list',
                    OptionListValue::fromArray(
                        array_map(
                            function (int $key) use ($allowed) {
                                return ListOptionValue::withValueAsLabel($key, $allowed[$key]);
                            },
                            array_keys($allowed)
                        )
                    )
                ),
                new NullOwner(),
                new DateTimeImmutable()
            )
        );
    }

    /**
     * @When I mark the property :arg1 of document :arg2 with parameters:
     */
    public function iMarkThePropertyOfDocumentWithParameters(string $property, string $documentId, TableNode $table)
    {
        $documentId = DocumentTypeId::fromString($documentId);
        $property = PropertyName::fromLocalizedString($property);
        $builder = DocumentTypeBuilder::parameters();

        foreach ($table->getHash() as $row) {
            $parameterName = $row['name'];
            $method = str_replace(
                ' ',
                '',
                lcfirst(
                    ucwords(
                        str_replace(
                            '-',
                            ' ',
                            $parameterName
                        )
                    )
                )
            );
            Assertion::methodExists(
                $method,
                $builder,
                'Parameter "%s" is not supported by the parameter builder.'
            );

            $supportedParameters = [
                'default-value' => DefaultValue::class,
                'date-format' => DateFormat::class,
            ];

            Assertion::keyExists(
                $supportedParameters,
                $parameterName,
                sprintf('Parameter "%s" is not supported yet, did your registered it.', $parameterName)
            );
            $parameterClass = $supportedParameters[$parameterName];
            $jsonArguments = $row['arguments'];
            Assert::assertJson($jsonArguments);

            $this->bus->dispatchCommand(
                new AddPropertyParameter(
                    $documentId,
                    $property,
                    $parameterName,
                    ParameterData::fromJson($parameterClass, $jsonArguments)
                )
            );
        }
    }

    /**
     * @When I mark the property :arg1 of document :arg2 with constraints:
     */
    public function iMarkThePropertyOfDocumentWithConstraints(string $property, string $document, TableNode $table)
    {
        $rows = $table->getHash();
        Assert::assertGreaterThan(0, count($rows));
        $documentId = DocumentTypeId::fromString($document);
        $propertyName = PropertyName::fromLocalizedString($property);
        foreach($rows as $row) {
            $name = $row['name'];
            $jsonString = $row['arguments'];
            Assert::assertJson($jsonString, 'arguments index do not contains valid json');
            $arguments = json_decode($jsonString, true);

            $this->bus->dispatchCommand(
                new AddPropertyConstraint(
                    $documentId, $propertyName, $name, $arguments
                )
            );
        }
    }

    /**
     * @When I enter the following values to document :arg1
     */
    public function iEnterTheFollowingValuesToDocument(string $documentId, TableNode $table)
    {
        foreach ($table->getHash() as $data) {
            $recordId = DocumentId::fromString($data['record-id']);
            Assert::assertJson($jsonString = $data['values'], 'values index do not contains valid json');
            $json = json_decode($jsonString, true);
            $property = $json['property'];
            $recordValue = RecordValueGuesser::guessValue($json['value']);

            try {
                $this->bus->dispatchCommand(
                    new CreateRecord(
                        DocumentTypeId::fromString($documentId),
                        $recordId,
                        [$property => $recordValue]
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
        $this->queries->dispatchQuery(
            $query = GetAllRecordsOfDocument::fromString($documentId)
        );

        /**
         * @var RecordRow[] $rows
         */
        $rows = $query->getResult();
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
                sprintf('Value of property "%s" is not as expected', $property)
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
        $this->getDocument($documentId)->acceptDocumentVisitor($visitor = new PropertyExtractor());
        Assert::assertTrue($visitor->getProperty($name)->hasConstraint('required'));
    }

    /**
     * @Then The property :arg1 of document :arg2 should have the following definition:
     */
    public function thePropertyOfDocumentShouldHaveTheFollowingDefinition($property, $documentId, TableNode $table)
    {
        $this->getDocument($documentId)->acceptDocumentVisitor($visitor = new PropertyExtractor());
        $definition = $visitor->getProperty($property);

        foreach ($table->getHash() as $options) {
            Assert::assertTrue($definition->typeIs($options['type']));
            Assert::assertTrue(
                $definition->hasConstraint($options['constraint'])
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
