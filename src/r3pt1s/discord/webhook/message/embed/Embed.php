<?php

namespace r3pt1s\discord\webhook\message\embed;

use Closure;
use InvalidArgumentException;
use LogicException;
use pocketcloud\cloud\util\misc\Writeable;
use r3pt1s\discord\webhook\util\WebhookHelper;

final class Embed implements Writeable {

    public const int MAX_FIELDS = 25;
    public const int MAX_TITLE_LENGTH = 256;
    public const int MAX_DESCRIPTION_LENGTH = 4096;

    private ?string $title = null;
    private ?string $description = null;
    private ?string $url = null;
    private ?int $timestamp = null;
    private ?int $color = null;

    private array $fields = [];

    private ?EmbedAuthor $author = null;
    private ?EmbedFooter $footer = null;
    private ?EmbedImage $image = null;
    private ?EmbedImage $thumbnail = null;
    private ?EmbedVideo $video = null;
    private ?EmbedProvider $provider = null;

    private function __construct() {}

    public function setTitle(?string $title): self {
        if ($title !== null && strlen($title) > self::MAX_TITLE_LENGTH) throw new LogicException("Embed titles are limited to ".self::MAX_TITLE_LENGTH . " characters");
        $this->title = $title;
        return $this;
    }

    public function setDescription(?string $description): self {
        if ($description !== null && strlen($description) > self::MAX_DESCRIPTION_LENGTH) throw new LogicException("Embed descriptions are limited to ".self::MAX_DESCRIPTION_LENGTH . " characters");
        $this->description = $description;
        return $this;
    }

    public function setUrl(?string $url): self {
        WebhookHelper::validateUrl($url);
        $this->url = $url;
        return $this;
    }

    public function setTimestamp(?int $timestamp): self {
        $this->timestamp = $timestamp;
        return $this;
    }

    public function setTimestampNow(): self {
        $this->timestamp = time();
        return $this;
    }

    public function setColor(?int $color): self {
        $this->color = $color;
        return $this;
    }

    public function setColorRgb(int $red, int $green, int $blue): self {
        $this->setColorHex(sprintf("#%02x%02x%02x", $red, $green, $blue));
        return $this;
    }

    public function setColorHex(string $hex): self {
        $this->color = hexdec(ltrim($hex, "#"));
        return $this;
    }
    
    public function addField(string $name, string $value, ?bool $inline = null): self {
        $this->fields[] = EmbedField::create($name, $value, $inline);
        return $this;
    }

    public function addFieldIf(Closure $conditionFn, string $name, string $value, ?bool $inline = null): self {
        if ($conditionFn()) $this->addField($name, $value, $inline);
        return $this;
    }

    public function addFields(EmbedField ...$fields): self {
        foreach ($fields as $field) $this->fields[] = $field;
        return $this;
    }

    public function addFieldsIf(Closure $conditionFn, EmbedField ...$fields): self {
        if ($conditionFn()) $this->addFields(...$fields);
        return $this;
    }

    public function setAuthor(string $name, ?string $url = null, ?string $iconUrl = null, ?string $proxyIconUrl = null): self {
        WebhookHelper::validateUrl($url);
        WebhookHelper::validateUrl($iconUrl, "IconUrl");
        WebhookHelper::validateUrl($proxyIconUrl, "ProxyIconUrl");
        $this->author = EmbedAuthor::create($name, $url, $iconUrl, $proxyIconUrl);
        return $this;
    }

    public function setFooter(string $text, ?string $iconUrl = null, ?string $proxyIconUrl = null): self {
        WebhookHelper::validateUrl($iconUrl, "IconUrl");
        WebhookHelper::validateUrl($proxyIconUrl, "ProxyIconUrl");
        $this->footer = EmbedFooter::create($text, $iconUrl, $proxyIconUrl);
        return $this;
    }

    public function setImage(string $url, ?string $proxyUrl = null, ?int $height = null, ?int $width = null): self {
        WebhookHelper::validateUrl($url);
        WebhookHelper::validateUrl($proxyUrl, "ProxyUrl");
        $this->image = EmbedImage::create($url, $proxyUrl, $height, $width);
        return $this;
    }

    public function setThumbnail(string $url, ?string $proxyUrl = null, ?int $height = null, ?int $width = null): self {
        WebhookHelper::validateUrl($url);
        WebhookHelper::validateUrl($proxyUrl, "ProxyUrl");
        $this->thumbnail = EmbedImage::create($url, $proxyUrl, $height, $width);
        return $this;
    }

    public function setVideo(string $url, ?string $proxyUrl = null, ?int $height = null, ?int $width = null): self {
        WebhookHelper::validateUrl($url);
        WebhookHelper::validateUrl($proxyUrl, "ProxyUrl");
        $this->video = EmbedVideo::create($url, $proxyUrl, $height, $width);
        return $this;
    }

    public function setProvider(?string $name = null, ?string $url = null): self {
        $this->provider = EmbedProvider::create($name, $url);
        return $this;
    }

    public function clearFields(): self {
        $this->fields = [];
        return $this;
    }

    public function getTitle(): ?string {
        return $this->title;
    }

    public function getDescription(): ?string {
        return $this->description;
    }

    public function getUrl(): ?string {
        return $this->url;
    }

    public function getTimestamp(): ?int {
        return $this->timestamp;
    }

    public function getColor(): ?int {
        return $this->color;
    }

    public function getFields(): array {
        return $this->fields;
    }

    public function getAuthor(): ?EmbedAuthor {
        return $this->author;
    }

    public function getFooter(): ?EmbedFooter {
        return $this->footer;
    }

    public function getImage(): ?EmbedImage {
        return $this->image;
    }

    public function getThumbnail(): ?EmbedImage {
        return $this->thumbnail;
    }

    public function getVideo(): ?EmbedVideo {
        return $this->video;
    }

    public function getProvider(): ?EmbedProvider {
        return $this->provider;
    }

    public function write(): array {
        return WebhookHelper::removeNullFields([
            "title" => $this->title,
            "description" => $this->description,
            "url" => $this->url,
            "timestamp" => $this->timestamp ? gmdate("c", $this->timestamp) : null,
            "color" => $this->color,
            "fields" => array_map(fn(EmbedField $field) => $field->write(), $this->fields),
            "author" => $this->author?->write(),
            "footer" => $this->footer?->write(),
            "image" => $this->image?->write(),
            "thumbnail" => $this->thumbnail?->write(),
            "video" => $this->video?->write(),
            "provider" => $this->provider?->write()
        ]);
    }

    public static function create(): self {
        return new self();
    }
}