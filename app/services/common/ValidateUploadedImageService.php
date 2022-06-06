<?php
namespace App\services\common;

class ValidateUploadedImageService
{
    /**
     * file requires 'tmp_name' & 'size' keys
     */
    public static function execute(array $file)
    {
        // ファイル形式の確認
        $valid_types = ['image/jpg', 'image/jpeg', 'image/png', 'image/gif'];
        if (!in_array(mime_content_type($file['tmp_name']), $valid_types, true)) {
            throw ServerException::invalidRequest(display_text: '不正なファイル形式です');
        }

        // 画像サイズの確認
        if ($file['size'] > 10000000) {
            throw ServerException::invalidRequest(display_text: 'アップロードできるファイルサイズの上限は10MBです');
        }
    }
}
