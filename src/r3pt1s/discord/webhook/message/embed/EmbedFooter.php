<?php

namespace r3pt1s\discord\webhook\message\embed;

use pocketcloud\cloud\util\misc\Writeable;
use r3pt1s\discord\webhook\util\WebhookHelper;

final readonly class EmbedFooter implements Writeable {

    private function __construct(
        private string $text,
        private ?string $iconUrl = null,
        private ?string $proxyIconUrl = null
    ) {}

    public function getText(): string {
        return $this->text;
    }

    public function getIconUrl(): ?string {
        return $this->iconUrl;
    }

    public function getProxyIconUrl(): ?string {
        return $this->proxyIconUrl;
    }

    public function write(): array {
        return WebhookHelper::removeNullFields([
            "text" => $this->text,
            "icon_url" => $this->iconUrl,
            "proxy_icon_url" => $this->proxyIconUrl
        ]);
    }

    public static function create(string $text, ?string $iconUrl = null, ?string $proxyIconUrl = null): self {
        return new self($text, $iconUrl, $proxyIconUrl);
    }
}