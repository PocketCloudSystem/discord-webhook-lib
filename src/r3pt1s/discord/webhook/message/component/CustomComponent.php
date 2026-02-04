<?php

namespace r3pt1s\discord\webhook\message\component;

use InvalidArgumentException;

abstract class CustomComponent extends MessageComponent {

    public const int MIN_CUSTOM_ID_LENGTH = 1;
    public const int MAX_CUSTOM_ID_LENGTH = 100;

    public function __construct(
        private readonly string $customId
    ) {
        parent::__construct();
        if ($this->customId == "") throw new InvalidArgumentException('$customId cannot be empty');
        if (
            strlen($this->customId) < self::MIN_CUSTOM_ID_LENGTH ||
            strlen($this->customId) > self::MAX_CUSTOM_ID_LENGTH
        ) throw new InvalidArgumentException('$customId cannot be less than ' . self::MIN_CUSTOM_ID_LENGTH . ' or greater than ' . self::MAX_CUSTOM_ID_LENGTH);
        $this->appendData(["custom_id" => $this->customId]);
    }

    public function getCustomId(): string {
        return $this->customId;
    }
}