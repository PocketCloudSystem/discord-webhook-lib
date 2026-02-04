<?php

namespace r3pt1s\discord\webhook\message\embed;

use JsonSerializable;
use r3pt1s\discord\webhook\WebhookHelper;

final readonly class EmbedImage implements JsonSerializable {

    private function __construct(
        private string $url,
        private ?string $proxyUrl = null,
        private ?int $height = null,
        private ?int $width = null
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

    public static function create(string $url, ?string $proxyUrl = null, ?int $height = null, ?int $width = null): EmbedImage {
        return new self($url, $proxyUrl, $height, $width);
    }
}