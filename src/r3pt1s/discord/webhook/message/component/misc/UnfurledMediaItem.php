<?php

namespace r3pt1s\discord\webhook\message\component\misc;

use JsonSerializable;
use pmmp\thread\ThreadSafe;
use r3pt1s\discord\webhook\WebhookHelper;

final class UnfurledMediaItem extends ThreadSafe implements JsonSerializable {

    private function __construct(private readonly string $url) {}


    public function jsonSerialize(): array {
        return WebhookHelper::removeNullFields([
            "url" => $this->url,
        ]);
    }

    public static function create(string $url): self {
        return new self($url);
    }
}