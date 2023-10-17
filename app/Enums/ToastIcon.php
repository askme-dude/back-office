<?php

namespace App\Enums;

enum ToastIcon: string
{
    case SUCCESS = 'success';
    case ERROR = 'error';
    case WARNING = 'warning';
    case INFO = 'info';
    case QUESTION = 'question';
}
