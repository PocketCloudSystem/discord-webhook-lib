<?php

namespace r3pt1s\discord\webhook\message\embed;

use pocketcloud\cloud\util\misc\Writeable;
use r3pt1s\discord\webhook\util\WebhookHelper;

final readonly class EmbedField implements Writeable {

    private function __construct(
        private string $name,
        private string $value,
        private ?bool $inline = null
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

    public function write(): array {
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