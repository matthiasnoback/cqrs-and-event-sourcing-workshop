<?php

namespace Test\Unit\Twitsup\Domain\Model\Tweet;

use EventSourcing\Aggregate\Testing\RecordedEventsEqual;
use Ramsey\Uuid\Uuid;
use Twitsup\Domain\Model\Tweet\Tweet;
use Twitsup\Domain\Model\Tweet\Tweeted;

class TweetTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function a_tweet_can_be_tweeted()
    {
        $id = Uuid::uuid4();
        $text = 'The text';
        $tweet = Tweet::createWithText($id, $text);

        $this->assertThat([new Tweeted($id, $text)], new RecordedEventsEqual($tweet));
    }
}
