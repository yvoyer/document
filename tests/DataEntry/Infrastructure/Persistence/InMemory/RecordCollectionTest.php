<?php declare(strict_types=1);

namespace Star\Component\Document\DataEntry\Infrastructure\Persistence\InMemory;

use PHPUnit\Framework\TestCase;
use Star\Component\Document\DataEntry\Domain\Model\DocumentRecord;
use Star\Component\Document\DataEntry\Domain\Model\RecordId;
use Star\Component\Identity\Exception\EntityNotFoundException;

final class RecordCollectionTest extends TestCase
{
    /**
     * @var RecordCollection
     */
    private $collection;

    public function setUp()
    {
        $this->collection = new RecordCollection();
    }

    public function test_it_should_save_the_record()
    {
        $this->assertCount(0, $this->collection);

        $this->collection->saveRecord(
            $id = new RecordId('r1'),
            $record = $this->createMock(DocumentRecord::class)
        );

        $this->assertCount(1, $this->collection);
        $this->assertTrue($this->collection->recordExists($id));
        $this->assertSame($record, $this->collection->getRecordWithIdentity($id));
    }

    public function test_it_should_throw_exception_when_not_found()
    {
        $id = new RecordId('not-found');
        $this->assertCount(0, $this->collection);
        $this->assertFalse($this->collection->recordExists($id));

        $this->expectException(EntityNotFoundException::class);
        $this->expectExceptionMessage(EntityNotFoundException::objectWithIdentity($id)->getMessage());
        $this->collection->getRecordWithIdentity($id);
    }
}
