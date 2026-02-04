<?php

namespace r3pt1s\discord\webhook\message\component\misc;

use pocketcloud\cloud\util\misc\Writeable;
use r3pt1s\discord\webhook\util\WebhookHelper;

final readonly class UnfurledMediaItem implements Writeable {

    private function __construct(private string $url) {}

    public function write(): array {
        return WebhookHelper::removeNullFields([
            "url" => $this->url
        ]);
    }

    public static function create(string $url): self {
        return new self($url);
    }
}