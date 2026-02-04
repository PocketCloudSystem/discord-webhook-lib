<?php

namespace r3pt1s\discord\webhook\message\embed;

use JsonSerializable;
use pmmp\thread\ThreadSafe;
use r3pt1s\discord\webhook\WebhookHelper;

final class EmbedVideo extends ThreadSafe implements JsonSerializable {

    private function __construct(
        private readonly string $url,
        private readonly ?string $proxyUrl = null,
        private readonly ?int $height = null,
        private readonly ?int $width = null
    ) {}

    public function getUrl(): string {
        return $this->url;
    }

    public function getProxyUrl(): ?string {
        return $this->proxyUrl;
    }

    public function getHeight(): ?int {
        return $this->height;
    }

    public function getWidth(): ?int {
        return $this->width;
    }

    public function jsonSerialize(): array {
        return WebhookHelper::removeNullFields([
            "url" => $this->url,
            "proxy_url" => $this->proxyUrl,
            "height" => $this->height,
            "width" => $this->width
        ]);
    }

    public static function create(string $url, ?string $proxyUrl = null, ?int $height = null, ?int $width = null): EmbedVideo {
        return new self($url, $proxyUrl, $height, $width);
    }
}