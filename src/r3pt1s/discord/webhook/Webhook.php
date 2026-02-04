<?php

namespace r3pt1s\discord\webhook;

use r3pt1s\discord\webhook\message\Message;

final readonly class Webhook {

    /**
     * @param string $url The base discord webhook url
     */
    public function __construct(private string $url) {}

    public function createMessage(bool $wait, ?string $threadId = null, bool $withComponents = false): Message {
        return new Message($wait, $threadId, $withComponents, $this);
    }

    public function getUrl(): string {
        return $this->url;
    }

    public static function create(string $url): self {
        return new self($url);
    }
}