<?php

namespace Simple\EventStore;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Schema\Schema;

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
        try {
            $this->connection->insert($this->tableName, $rawEventData);
        } catch (\Exception $exception) {
            // @todo Convert exception related to unique constraint to ConcurrencyError
            throw $exception;
        }
    }

    public function setUp()
    {
        $schema = new Schema();

        // @todo extract class for Schema definition
        $table = $schema->createTable($this->tableName);

        $table->addColumn('event_id', 'string', ['length' => 36]);
        $table->addColumn('playhead', 'integer');
        $table->addColumn('event_name', 'string', ['length' => 100]);
        $table->addColumn('aggregate_type', 'string', ['length' => 255]);
        $table->addColumn('aggregate_id', 'string', ['length' => 36]);
        $table->addColumn('payload', 'text');
        $table->addColumn('created_at', 'string', ['length' => 50]);
        $table->setPrimaryKey(['event_id']);
        // for faster reconstitute queries:
        $table->addIndex(['aggregate_type', 'aggregate_id']);
        // for optimistic locking:
        $table->addUniqueIndex(['aggregate_type', 'aggregate_id', 'playhead']);

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
            ->orderBy('playhead', 'ASC')
            ->andWhere('aggregate_type = :aggregate_type')
            ->setParameter('aggregate_type', $aggregateType)
            ->andWhere('aggregate_id = :aggregate_id')
            ->setParameter('aggregate_id', $aggregateId);

        $stmt = $queryBuilder->execute();

        while ($record = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            yield $record;
        }
    }

    public function transactional(callable $callable)
    {
        $this->connection->beginTransaction();

        try {
            call_user_func($callable);

            $this->connection->commit();
        } catch (\Exception $previous) {
            $this->connection->rollBack();
            throw TransactionFailed::forLowerLevelReason($previous);
        }
    }
}
