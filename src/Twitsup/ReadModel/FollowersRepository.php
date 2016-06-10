<?php

namespace Twitsup\ReadModel;

use GraphAware\Neo4j\Client\ClientBuilder;

final class FollowersRepository
{
    public function __construct()
    {
        $this->client = ClientBuilder::create()
            ->addConnection('default', 'http://neo4j:neo4j@localhost:7474')
            ->build();
    }

    /**
     * @param string $followeeId
     * @return string[]
     */
    public function getFollowerIds(string $followeeId)
    {
        /** @var $result \GraphAware\Bolt\Result\Result */
        $result = $this->client->run(
            'MATCH (follower:User)-[:FOLLOWS]->(followee:Person { user_id: {followeeId} ) RETURN follower',
            [
                'followeeId' => $followeeId
            ]
        );

        foreach ($result->getRecords() as $record) {
            yield $record->value('user_id');
        }
    }

    public function follow($followerId, $followeeId)
    {
        $this->mergeNodes($followerId, $followeeId);
        $this->client->run(
            <<<EOD
MATCH (follower:User { user_id: {followerId} }), (followee:User { user_id: {followeeId} })
CREATE (follower)-[r:FOLLOWS]->(followee)
EOD
            , [
                'followerId' => $followerId,
                'followeeId' => $followeeId
            ]
        );
    }

    public function unfollow($followerId, $followeeId)
    {
        $this->mergeNodes($followerId, $followeeId);
        $this->client->run(
            <<<EOD
MATCH (follower:User { user_id: {followerId} })-[relation:FOLLOWS]->(followee:User { user_id: {followeeId} })
DELETE relation
EOD
            , [
                'followerId' => $followerId,
                'followeeId' => $followeeId
            ]
        );
    }

    private function mergeNodes($followerId, $followeeId)
    {
        $stack = $this->client->stack();
        $stack->push(
            'MERGE (follower:User { user_id: {userId} }) RETURN follower',
            [
                'userId' => $followerId
            ]
        );
        $stack->push(
            'MERGE (followee:User { user_id: {userId} }) RETURN followee',
            [
                'userId' => $followeeId
            ]
        );
        $this->client->runStack($stack);
    }
}
