<?php

namespace r3pt1s\discord\webhook\message\embed;

use JsonSerializable;
use pmmp\thread\ThreadSafe;
use r3pt1s\discord\webhook\WebhookHelper;

final class EmbedField extends ThreadSafe implements JsonSerializable {

    private function __construct(
        private readonly string $name,
        private readonly string $value,
        private readonly ?bool $inline = null
    ) {}

    public function getName(): string {
        return $this->name;
    }

    public function getValue(): string {
        return $this->value;
    }

    public function getInline(): ?bool {
        return $this->inline;
    }

    public function jsonSerialize(): array {
        return WebhookHelper::removeNullFields([
            "name" => $this->name,
            "value" => $this->value,
            "inline" => $this->inline
        ]);
    }

    public static function create(string $name, string $value, ?bool $inline = null): self {
        return new self($name, $value, $inline);
    }
}