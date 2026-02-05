<?php

namespace r3pt1s\discord\webhook\emoji;

use pocketcloud\cloud\util\misc\Writeable;
use r3pt1s\discord\webhook\util\WebhookHelper;

final readonly class PartialEmoji implements Writeable {

    public function __construct(
        private ?string $emojiId,
        private string $emojiName
    ) {}

    public function getEmojiId(): string {
        return $this->emojiId;
    }

    public function getEmojiName(): string {
        return $this->emojiName;
    }

    public function write(): array {
        return WebhookHelper::removeNullFields([
            "id" => $this->emojiId,
            "name" => $this->emojiName
        ]);
    }

    public static function create(string $emojiId, string $emojiName): self {
        return new self($emojiId, $emojiName);
    }

    public static function fromUnicode(string $unicode): self {
        return new self(null, $unicode);
    }
}