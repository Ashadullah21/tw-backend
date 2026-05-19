<?php

namespace App\Models;

/**
 * PHP 8.1+ backed enum representing the status of a download attempt.
 */
enum DownloadStatus: string
{
    case Success = 'success';
    case Failed  = 'failed';
}
