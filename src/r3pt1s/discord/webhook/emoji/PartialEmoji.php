<?php

namespace r3pt1s\discord\webhook\emoji;

use JsonSerializable;
use r3pt1s\discord\webhook\WebhookHelper;

final readonly class PartialEmoji implements JsonSerializable {

    public function __construct(
        private string $emojiId,
        private string $emojiName
    ) {}

    public function getEmojiId(): string {
        return $this->emojiId;
    }

    public function getEmojiName(): string {
        return $this->emojiName;
    }

    public function jsonSerialize(): array {
        return WebhookHelper::removeNullFields([
            "id" => $this->emojiId,
            "name" => $this->emojiName
        ]);
    }

    public static function create(string $emojiId, string $emojiName): self {
        return new self($emojiId, $emojiName);
    }
}