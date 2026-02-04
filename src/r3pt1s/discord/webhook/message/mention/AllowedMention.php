<?php

namespace r3pt1s\discord\webhook\message\mention;

use JsonSerializable;
use r3pt1s\discord\webhook\WebhookHelper;

final class AllowedMention implements JsonSerializable {

    public const int MAX_ROLES = 100;
    public const int MAX_USERS = 100;

    private array $allowedMentions = [];
    private array $roles = [];
    private array $users = [];

    public function allow(AllowedMentionType $type): self {
        $this->allowedMentions[] = $type->value;
        return $this;
    }

    public function mentionedRoles(string ...$roleIds): self {
        if (count($roleIds) > self::MAX_ROLES) $roleIds = array_slice($roleIds, 0, self::MAX_ROLES);
        $this->roles = $roleIds;
        return $this;
    }

    public function mentionedUsers(string ...$userIds): self {
        if (count($userIds) > self::MAX_USERS) $userIds = array_slice($userIds, 0, self::MAX_USERS);
        $this->users = $userIds;
        return $this;
    }

    public function getAllowedMentions(): array {
        return $this->allowedMentions;
    }

    public function getRoles(): array {
        return $this->roles;
    }

    public function getUsers(): array {
        return $this->users;
    }

    public function jsonSerialize(): array {
        return WebhookHelper::removeNullFields([
            "parse" => $this->allowedMentions,
            "roles" => $this->roles,
            "users" => $this->users
        ]);
    }

    public static function create(): self {
        return new self();
    }
}