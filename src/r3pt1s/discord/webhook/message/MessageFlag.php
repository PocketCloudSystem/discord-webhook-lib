<?php

namespace r3pt1s\discord\webhook\message;

enum MessageFlag: int {

    case SUPPRESS_EMBEDS = 1 << 2;
    case SUPPRESS_NOTIFICATIONS = 1 << 12;
    case IS_COMPONENTS_V2 = 1 << 15;
}