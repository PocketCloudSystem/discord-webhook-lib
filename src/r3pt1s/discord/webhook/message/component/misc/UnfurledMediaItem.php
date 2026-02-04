<?php

namespace r3pt1s\discord\webhook\message\component\misc;

use JsonSerializable;
use r3pt1s\discord\webhook\WebhookHelper;

final readonly class UnfurledMediaItem implements JsonSerializable {

    private function __construct(private string $url) {}


    public function jsonSerialize(): array {
        return WebhookHelper::removeNullFields([
            "url" => $this->url,
        ]);
    }

    public static function create(string $url): self {
        return new self($url);
    }
}