<?php

namespace r3pt1s\discord\webhook\message\attachment;

use JsonSerializable;
use r3pt1s\discord\webhook\WebhookHelper;

final readonly class Attachment implements JsonSerializable {

    public function __construct(
        private int $id,
        private string $fileName
    ) {}

    public function getId(): int {
        return $this->id;
    }

    public function getFileName(): string {
        return $this->fileName;
    }

    public function jsonSerialize(): array {
        return WebhookHelper::removeNullFields([
            "id" => $this->id,
            "filename" => $this->fileName
        ]);
    }
}