<?php

namespace r3pt1s\discord\webhook\message\component\misc;

use pocketcloud\cloud\util\misc\Writeable;

final readonly class SelectDefaultValue implements Writeable {

    private function __construct(
        private string $snowflakeId,
        private DefaultValueRepresentationType $representationType
    ) {}

    public function getSnowflakeId(): string {
        return $this->snowflakeId;
    }

    public function getRepresentationType(): DefaultValueRepresentationType {
        return $this->representationType;
    }

    public function write(): array {
        return [
            "id" => $this->snowflakeId,
            "type" => $this->representationType->value,
        ];
    }

    public static function create(string $snowflakeId, DefaultValueRepresentationType $representationType): self {
        return new self($snowflakeId, $representationType);
    }
}