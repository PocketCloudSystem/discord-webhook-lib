<?php

namespace r3pt1s\discord\webhook;

use message\Message;
use pmmp\thread\ThreadSafe;

final class Webhook extends ThreadSafe {

    private ?Message $message = null;

    /**
     * @param string $url The discord webhook url
     * @param bool $wait Waits for server confirmation of message send before response, and returns the created message body (defaults to false; when false a message that is not saved does not return an error)
     * @param string|null $threadId Send a message to the specified thread within a webhook's channel. The thread will automatically be unarchived.
     * @param bool $withComponents whether to respect the components field of the request. When enabled, allows application-owned webhooks to use all components and non-owned webhooks to use non-interactive components. (defaults to false)
     */
    public function __construct(
        private readonly string $url,
        private readonly bool $wait = false,
        private readonly ?string $threadId = null,
        private readonly bool $withComponents = false
    ) {}

    public function createMessage(): Message {
        return $this->message ??= new Message();
    }

    public function getUrl(): string {
        return $this->url;
    }

    public function isWait(): bool {
        return $this->wait;
    }

    public function getThreadId(): ?string {
        return $this->threadId;
    }

    public function isWithComponents(): bool {
        return $this->withComponents;
    }
}