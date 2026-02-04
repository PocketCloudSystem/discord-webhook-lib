<?php

namespace r3pt1s\discord\webhook\message\mention;

use JsonSerializable;
use pmmp\thread\ThreadSafe;
use pmmp\thread\ThreadSafeArray;
use r3pt1s\discord\webhook\WebhookHelper;

final class AllowedMention extends ThreadSafe implements JsonSerializable {

    public const int MAX_ROLES = 100;
    public const int MAX_USERS = 100;

    private ThreadSafeArray $allowedMentions;
    private ThreadSafeArray $roles;
    private ThreadSafeArray $users;

    public function __construct() {
        $this->allowedMentions = new ThreadSafeArray();
        $this->roles = new ThreadSafeArray();
        $this->users = new ThreadSafeArray();
    }

    public function allow(AllowedMentionType $type): self {
        if (!isset($this->allowedMentions[$type])) $this->allowedMentions[] = $type->value;
        return $this;
    }

    public function mentionedRoles(string ...$roleIds): self {
        if (count($roleIds) > self::MAX_ROLES) $roleIds = array_slice($roleIds, 0, self::MAX_ROLES);
        $this->roles = ThreadSafeArray::fromArray($roleIds);
        return $this;
    }

    public function mentionedUsers(string ...$userIds): self {
        if (count($userIds) > self::MAX_USERS) $userIds = array_slice($userIds, 0, self::MAX_USERS);
        $this->users = ThreadSafeArray::fromArray($userIds);
        return $this;
    }

    public function getAllowedMentions(): ThreadSafeArray {
        return $this->allowedMentions;
    }

    public function getRoles(): ThreadSafeArray {
        return $this->roles;
    }

    public function getUsers(): ThreadSafeArray {
        return $this->users;
    }

    public function jsonSerialize(): array {
        return WebhookHelper::removeNullFields([
            "parse" => (array) $this->allowedMentions,
            "roles" => (array) $this->roles,
            "users" => (array) $this->users
        ]);
    }

    public static function create(): self {
        return new self();
    }
}