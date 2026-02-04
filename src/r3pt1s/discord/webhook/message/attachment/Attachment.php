<?php

namespace r3pt1s\discord\webhook\message\attachment;

use pocketcloud\cloud\util\misc\Writeable;
use r3pt1s\discord\webhook\util\WebhookHelper;

final readonly class Attachment implements Writeable {

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

    public function write(): array {
        return WebhookHelper::removeNullFields([
            "id" => $this->id,
            "filename" => $this->fileName
        ]);
    }
}