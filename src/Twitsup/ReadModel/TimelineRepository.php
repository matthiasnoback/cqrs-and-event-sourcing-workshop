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
        $timelineFilePath = $this->databaseDirectory() . '/' . $userId . '.txt';

        // in case the file doesn't exist yet
        touch($timelineFilePath);

        return $timelineFilePath;
    }

    public function reset()
    {
        foreach (scandir($this->databaseDirectory()) as $file) {
            if ($file === '.' || $file === '..') {
                continue;
            }

            unlink($this->databaseDirectory() . '/' . $file);
        }
    }

    /**
     * @return string
     */
    private function databaseDirectory()
    {
        $directory = $this->databasePath . '/timeline';
        if (!is_dir($directory)) {
            mkdir($directory, 0777, true);
        }

        return $directory;
    }
}