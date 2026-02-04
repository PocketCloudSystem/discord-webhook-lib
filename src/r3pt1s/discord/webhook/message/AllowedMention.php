<?php

namespace r3pt1s\discord\webhook\message;

use pmmp\thread\ThreadSafe;
use pmmp\thread\ThreadSafeArray;

final class AllowedMention extends ThreadSafe {

    public const int MAX_ROLES = 100;
    public const int MAX_USERS = 100;

    private ThreadSafeArray $allowedMentions;
    private ThreadSafeArray $roles;
    private ThreadSafeArray $users;

    public function __construct() {}
}