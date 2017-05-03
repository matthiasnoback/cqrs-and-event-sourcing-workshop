<?php
declare(strict_types=1);

namespace Twitsup\Domain\Model\Tweet;

final class TweetedAt
{
    private const DATE_TIME_FORMAT = \DateTime::ATOM;

    /**
     * @var string
     */
    private $date;

    private function __construct(string $date)
    {
        $this->date = $date;
    }

    public static function fromDateTime(\DateTimeImmutable $dateTime): TweetedAt
    {
        return new self($dateTime->format(self::DATE_TIME_FORMAT));
    }

    public function __toString(): string
    {
        return $this->date;
    }

    public function toDateTime(): \DateTimeImmutable
    {
        return \DateTimeImmutable::createFromFormat(self::DATE_TIME_FORMAT, $this->date);
    }
}
