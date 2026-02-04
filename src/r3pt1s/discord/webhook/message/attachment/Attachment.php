<?php

namespace r3pt1s\discord\webhook\message\attachment;

use JsonSerializable;
use pmmp\thread\ThreadSafe;
use r3pt1s\discord\webhook\WebhookHelper;

final class Attachment extends ThreadSafe implements JsonSerializable {

    public function __construct(
        private readonly int $id,
        private readonly string $fileName
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