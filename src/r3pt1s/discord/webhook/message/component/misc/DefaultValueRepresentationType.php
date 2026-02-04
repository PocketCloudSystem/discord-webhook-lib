<?php

namespace r3pt1s\discord\webhook\message\component\misc;

enum DefaultValueRepresentationType: string {

    case USER = "user";
    case ROLE = "role";
    case CHANNEL = "channel";
}