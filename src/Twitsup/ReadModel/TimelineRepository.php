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
        $timelineFilePath = $this->databasePath . '/' . $followerId . '.timeline';
        touch($timelineFilePath);

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
}
