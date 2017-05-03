<?php
declare(strict_types=1);

namespace MeetupManagement\Domain\Model;

final class Title
{
    /**
     * @var string
     */
    private $title;

    private function __construct(string $title)
    {
        $this->title = $title;
    }

    public static function fromString(string $title): Title
    {
        return new self($title);
    }
}
