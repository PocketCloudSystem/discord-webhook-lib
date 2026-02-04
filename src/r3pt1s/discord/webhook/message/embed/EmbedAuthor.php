<?php

namespace r3pt1s\discord\webhook\message\embed;

use JsonSerializable;
use pmmp\thread\ThreadSafe;
use r3pt1s\discord\webhook\WebhookHelper;

final class EmbedAuthor extends ThreadSafe implements JsonSerializable {

    private function __construct(
        private readonly string $name,
        private readonly ?string $url = null,
        private readonly ?string $iconUrl = null,
        private readonly ?string $proxyIconUrl = null
    ) {}

    public function getName(): string {
        return $this->name;
    }

    public function getUrl(): ?string {
        return $this->url;
    }

    public function getIconUrl(): ?string {
        return $this->iconUrl;
    }

    public function getProxyIconUrl(): ?string {
        return $this->proxyIconUrl;
    }

    public function jsonSerialize(): array {
        return WebhookHelper::removeNullFields([
            "name" => $this->name,
            "url" => $this->url,
            "icon_url" => $this->iconUrl,
            "proxy_icon_url" => $this->proxyIconUrl
        ]);
    }

    public static function create(string $name, ?string $url = null, ?string $iconUrl = null, ?string $proxyIconUrl = null): self {
        return new self($name, $url, $iconUrl, $proxyIconUrl);
    }
}