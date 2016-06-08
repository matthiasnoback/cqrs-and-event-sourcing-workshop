<?php

namespace EventSourcing\EventStore\Storage;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Schema\Schema;
use EventSourcing\EventStore\StorageFacility;

class DoctrineDbalStorageFacility implements StorageFacility
{
    /**
     * @var Connection
     */
    private $connection;

    /**
     * @var string
     */
    private $tableName;

    public function __construct(Connection $connection, string $tableName)
    {
        $this->connection = $connection;
        $this->tableName = $tableName;
    }

    public function persistRawEvent(array $rawEventData)
    {
        $this->connection->insert($this->tableName, $rawEventData);
    }

    public function setUp()
    {
        $schema = new Schema();

        $table = $schema->createTable($this->tableName);
        $table->addColumn('event_id', 'string', ['length' => 36]);
        $table->addColumn('event_type', 'string', ['length' => 100]);
        $table->addColumn('aggregate_type', 'string', ['length' => 255]);
        $table->addColumn('aggregate_id', 'string', ['length' => 36]);
        $table->addColumn('payload', 'text');
        $table->addColumn('created_at', 'string', ['length' => 50]);
        $table->setPrimaryKey(['event_id']);

        $queries = $schema->toSql($this->connection->getDatabasePlatform());

        foreach ($queries as $query) {
            $this->connection->executeQuery($query);
        }
    }

    public function loadRawEvents(string $aggregateType, string $aggregateId)
    {
        $queryBuilder = $this->connection->createQueryBuilder();

        $queryBuilder
            ->select('*')
            ->from($this->tableName, $this->tableName)
            ->orderBy('created_at', 'ASC')
            ->andWhere('aggregate_type = :aggregate_type')
            ->setParameter('aggregate_type', $aggregateType)
            ->andWhere('aggregate_id = :aggregate_id')
            ->setParameter('aggregate_id', $aggregateId);

        $stmt = $queryBuilder->execute();

        while ($record = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            yield $record;
        }
    }
}
