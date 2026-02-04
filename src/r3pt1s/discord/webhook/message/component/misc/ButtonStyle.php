<?php

namespace r3pt1s\discord\webhook\message\component\misc;

enum ButtonStyle: int {

    case PRIMARY = 1;
    case SECONDARY = 2;
    case SUCCESS = 3;
    case DANGER = 4;
    case LINK = 5;
    case PREMIUM = 6;
}