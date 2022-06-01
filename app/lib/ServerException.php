<?php
namespace App\lib;

use App\lib\ServerExceptionName;
use Exception;

class ServerException extends Exception
{
    private function __construct(
        public ServerExceptionName $name,
        /** null でないとき、HTML でこの値を表示する */
        public string $display_text,
        public ?Exception $inner,
    ) {
    }
    
    public static function noSuchRecord(?Exception $inner = null, ?string $display_text = 'データが存在しません'): self
    {
        return new self(ServerExceptionName::NO_SUCH_RECORD, $display_text, $inner);
    }

    public static function internal(?Exception $inner = null, ?string $display_text = '不明なエラーが発生しました'): self
    {
        return new self(ServerExceptionName::INTERNAL, $display_text, $inner);
    }

    public static function emailAlreadyExists(?Exception $inner = null, ?string $display_text = '指定したメールアドレスは既に利用されています。ログインするか、違うメールアドレスで再度お試しください'): self
    {
        return new self(ServerExceptionName::EMAIL_ALREADY_EXISTS, $display_text, $inner);
    }

    public static function database(?Exception $inner = null, ?string $display_text = '不明なエラーが発生しました'): self
    {
        return new self(ServerExceptionName::DATABASE_ERROR, $display_text, $inner);
    }

    public static function invalidRequest(?Exception $inner = null, ?string $display_text = '不正な入力です'): self
    {
        return new self(ServerExceptionName::INVALID_REQUEST, $display_text, $inner);
    }
}
