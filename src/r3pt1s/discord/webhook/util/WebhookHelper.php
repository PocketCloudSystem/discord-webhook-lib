<?php

namespace r3pt1s\discord\webhook\util;

final class WebhookHelper {

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