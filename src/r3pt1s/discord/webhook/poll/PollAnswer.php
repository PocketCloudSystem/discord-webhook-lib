<?php

namespace r3pt1s\discord\webhook\poll;

use pocketcloud\cloud\util\misc\Writeable;
use r3pt1s\discord\webhook\emoji\PartialEmoji;
use r3pt1s\discord\webhook\util\WebhookHelper;

final readonly class PollAnswer implements Writeable {

    public function __construct(
        private int $answerId,
        private string $answer,
        private ?PartialEmoji $emoji = null
    ) {}

    public function getAnswerId(): int {
        return $this->answerId;
    }

    public function getAnswer(): string {
        return $this->answer;
    }

    public function getEmoji(): ?PartialEmoji {
        return $this->emoji;
    }

    public function write(): array {
        return WebhookHelper::removeNullFields([
            "answer_id" => $this->answerId,
            "poll_media" => [
                "text" => $this->answer,
                "emoji" => $this->emoji?->write()
            ]
        ]);
    }
}