<?php

namespace Twitsup\ReadModel;

final class TimelineRepository
{
    private $databasePath;

    public function __construct($databasePath)
    {

        $this->databasePath = $databasePath;
    }

    public function addToTimeline(string $followerId, array $userProfile, string $text, \DateTimeImmutable $tweetedAt)
    {
        $timelineFilePath = $this->timelineFilePathForUser($followerId);

        $handle = fopen($timelineFilePath, 'a+');
        fwrite($handle, sprintf(
            "%s (%s) tweeted on %s: %s\n",
            $userProfile['nickname'],
            $userProfile['username'],
            $tweetedAt->format('H:i'),
            $text
        ));
        fclose($handle);
    }

    public function timelineFor(string $userId) : string
    {
        return file_get_contents($this->timelineFilePathForUser($userId));
    }

    /**
     * @param string $userId
     * @return string
     */
    private function timelineFilePathForUser(string $userId) : string
    {
        $timelineFilePath = $this->databasePath . '/' . $userId . '.timeline';

        // in case the file doesn't exist yet
        touch($timelineFilePath);

        return $timelineFilePath;
    }
}
