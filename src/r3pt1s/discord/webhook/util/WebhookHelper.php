<?php

namespace r3pt1s\discord\webhook\util;

use InvalidArgumentException;

final class WebhookHelper {

    public static function validateUrl(?string $url, ?string $name = null): void {
        if ($url !== null && !filter_var($url, FILTER_VALIDATE_URL)) throw new InvalidArgumentException(($name ?? "Url") . " must be a valid URL");
    }

    public static function removeNullFields(array $data): array {
        foreach ($data as $i => &$value) {
            if ($value === null) {
                unset($data[$i]);
                continue;
            }

            if (is_array($value)) {
                $value = self::removeNullFields($value);
                if (empty($value)) unset($data[$i]);
            }
        }

        return $data;
    }
}