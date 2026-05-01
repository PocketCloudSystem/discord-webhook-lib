<?php

namespace r3pt1s\discord\webhook;

use pocketcloud\cloud\scheduler\AsyncPool;
use pocketcloud\cloud\util\promise\Promise;
use r3pt1s\discord\webhook\message\Message;
use r3pt1s\discord\webhook\task\DiscordSendDataTask;
use Throwable;

final class Webhook {

    private string $defaultUsername = "";
    private string $defaultAvatarUrl = "";

    /**
     * @param string $url The base discord webhook url
     */
    public function __construct(private readonly string $url) {}

    public function withDefaults(string $defaultUsername, string $defaultAvatarUrl): self {
        $this->defaultUsername = $defaultUsername;
        $this->defaultAvatarUrl = $defaultAvatarUrl;
        return $this;
    }

    public function createMessage(bool $wait = false, ?string $threadId = null, bool $withComponents = false): Message {
        return new Message($wait, $threadId, $withComponents, $this)->tap(function (Message $message): void {
            if ($this->defaultUsername !== "") $message->setUsername($this->defaultUsername);
            if ($this->defaultAvatarUrl !== "") $message->setAvatarUrl($this->defaultAvatarUrl);
        });
    }

    public function send(Message $message): Promise {
        $promise = new Promise();
        AsyncPool::getInstance()->submitTask(new DiscordSendDataTask(
            $this->url,
            $message->isWait(),
            $message->getThreadId(),
            $message->isWithComponents(),
            serialize($message->write()),
            static function (bool|string|Throwable $response, ?int $statusCode, string $curlError, int $curlErrno) use ($promise): void {
                if ($statusCode === null) {
                    $promise->reject([$response, $statusCode, $curlError, $curlErrno]);
                    return;
                }

                if (str_starts_with((string) $statusCode, "4") || str_starts_with((string) $statusCode, "5") || $response === false) {
                    $promise->reject([$response, $statusCode, $curlError, $curlErrno]);
                    return;
                }

                $promise->resolve([$response, $statusCode, $curlError, $curlErrno]);
            }
        ));

        return $promise;
    }

    public function getUrl(): string {
        return $this->url;
    }

    public static function create(string $url): self {
        return new self($url);
    }
}