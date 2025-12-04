<?php

namespace App\Enums;

enum SettingGroupEnum: string
{
    case SETTING_GROUP_GENERAL = "general";
    case SETTING_GROUP_MAIL = "mail";
    case SETTING_GROUP_SMS = "sms";
}
