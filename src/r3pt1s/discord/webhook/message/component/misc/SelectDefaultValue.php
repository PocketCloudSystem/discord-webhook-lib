<?php

namespace r3pt1s\discord\webhook\message\component\misc;

use JsonSerializable;
use pmmp\thread\ThreadSafe;

final class SelectDefaultValue extends ThreadSafe implements JsonSerializable {

    private function __construct(
        private readonly string $snowflakeId,
        private readonly DefaultValueRepresentationType $representationType
    ) {}

    public function getSnowflakeId(): string {
        return $this->snowflakeId;
    }

    public function getRepresentationType(): DefaultValueRepresentationType {
        return $this->representationType;
    }

    public function jsonSerialize(): array {
        return [
            "id" => $this->snowflakeId,
            "type" => $this->representationType->value,
        ];
    }

    public static function create(string $snowflakeId, DefaultValueRepresentationType $representationType): self {
        return new self($snowflakeId, $representationType);
    }
}