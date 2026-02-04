<?php

namespace r3pt1s\discord\webhook\message\mention;

enum AllowedMentionType: string {

    case ROLES = "roles";
    case USERS = "users";
    case EVERYONE = "everyone";
}