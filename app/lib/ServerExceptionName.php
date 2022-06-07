<?php
namespace App\lib;

enum ServerExceptionName: string
{
    case NO_SUCH_RECORD = 'no-such-record';
    case DATABASE_ERROR = 'database-error';
    case INTERNAL = 'internal';
    case EMAIL_ALREADY_EXISTS = 'email-already-exists';
    case INVALID_REQUEST = 'invalid-request';
    case CSRF_CHECK_FAILED = 'csrf-check-failed';
    case UNAUTHENTICATED =  'unauthenticated';
    case UNAUTHORIZED = 'unauthorized';
}
