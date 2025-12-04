<?php

namespace App\Enums;

enum FileDestinationEnum: string
{
    case USER_IMAGE_PATH = "user";
    case GENERAL_IMAGE_PATH = "general";
    case SETTINGS_IMAGE_PATH = "settings";
}
