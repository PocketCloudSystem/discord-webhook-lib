<?php

namespace r3pt1s\discord\webhook\message\embed;

use pocketcloud\cloud\util\misc\Writeable;
use r3pt1s\discord\webhook\util\WebhookHelper;

final readonly class EmbedProvider implements Writeable {

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

    public function write(): array {
        return WebhookHelper::removeNullFields([
            "name" => $this->name,
            "url" => $this->url
        ]);
    }

    public static function create(?string $name = null, ?string $url = null): self {
        return new self($name, $url);
    }
}