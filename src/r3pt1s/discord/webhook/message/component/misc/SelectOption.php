<?php

namespace r3pt1s\discord\webhook\message\component\misc;

use JsonSerializable;
use pmmp\thread\ThreadSafe;
use r3pt1s\discord\webhook\emoji\PartialEmoji;
use r3pt1s\discord\webhook\WebhookHelper;

final class SelectOption extends ThreadSafe implements JsonSerializable {

    private function __construct(
        private readonly string $label,
        private readonly string $value,
        private readonly ?string $description = null,
        private readonly ?PartialEmoji $emoji = null,
        private readonly ?bool $default = null
    ) {}

    public function getLabel(): string {
        return $this->label;
    }

    public function getValue(): string {
        return $this->value;
    }

    public function getDescription(): ?string {
        return $this->description;
    }

    public function getEmoji(): ?PartialEmoji {
        return $this->emoji;
    }

    public function getDefault(): ?bool {
        return $this->default;
    }

    public function jsonSerialize(): array {
        return WebhookHelper::removeNullFields([
            "label" => $this->label,
            "value" => $this->value,
            "description" => $this->description,
            "emoji" => $this->emoji,
            "default" => $this->default
        ]);
    }

    public static function create(string $label, string $value, ?string $description = null, ?PartialEmoji $emoji = null, ?bool $default = null): self {
        return new self($label, $value, $description, $emoji, $default);
    }
}