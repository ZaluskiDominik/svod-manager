<?php

namespace App\Common\Event;

class SendMailEvent extends AbstractEvent
{
    /** @var string */
    private $subject;

    /** @var string */
    private $from;

    /** @var string */
    private $to;

    /** @var string */
    private $body;

    public function __construct(string $to, string $subject, string $body, string $from = null)
    {
        $this->subject = $subject;
        $this->from = $from;
        $this->to = $to;
        $this->body = $body;

        if ($this->from === null) {
            $this->from = $_ENV['MAIL_DEFAULT_FROM'];
        }
    }

    public static function fromArray(array $data)
    {
        return new self(
            $data['to'],
            $data['subject'],
            $data['body'],
            $data['from'] ?? null
        );
    }

    public function toArray(): array
    {
        return [
            'to' => $this->getTo(),
            'subject' => $this->getSubject(),
            'body' => $this->getBody(),
            'from' => $this->getFrom()
        ];
    }

    public function jsonSerialize()
    {
        return $this->toArray();
    }

    public function getSubject(): string
    {
        return $this->subject;
    }

    public function setSubject(string $subject): void
    {
        $this->subject = $subject;
    }

    public function getFrom(): string
    {
        return $this->from;
    }

    public function setFrom(string $from): void
    {
        $this->from = $from;
    }

    public function getTo(): string
    {
        return $this->to;
    }

    public function setTo(string $to): void
    {
        $this->to = $to;
    }

    public function getBody(): string
    {
        return $this->body;
    }

    public function setBody(string $body): void
    {
        $this->body = $body;
    }
}
