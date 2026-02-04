<?php

namespace r3pt1s\discord\webhook\message\embed;

use JsonSerializable;
use r3pt1s\discord\webhook\WebhookHelper;

final readonly class EmbedProvider implements JsonSerializable {

    private function __construct(
        private ?string $name,
        private ?string $url
    ) {}

    public function getName(): ?string {
        return $this->name;
    }

    public function getUrl(): ?string {
        return $this->url;
    }

    public function jsonSerialize(): array {
        return WebhookHelper::removeNullFields([
            "name" => $this->name,
            "url" => $this->url
        ]);
    }

    public static function create(?string $name = null, ?string $url = null): self {
        return new self($name, $url);
    }
}