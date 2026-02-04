<?php

namespace r3pt1s\discord\webhook\poll;

use JsonSerializable;
use pmmp\thread\ThreadSafe;
use r3pt1s\discord\webhook\emoji\PartialEmoji;
use r3pt1s\discord\webhook\WebhookHelper;

final class PollAnswer extends ThreadSafe implements JsonSerializable {

    public function __construct(
        private readonly int $answerId,
        private readonly string $answer,
        private readonly ?PartialEmoji $emoji = null
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

    public function jsonSerialize(): array {
        return WebhookHelper::removeNullFields([
            "answer_id" => $this->answerId,
            "poll_media" => [
                "text" => $this->answer,
                "emoji" => $this->emoji
            ]
        ]);
    }
}