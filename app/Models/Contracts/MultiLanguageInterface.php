<?php
namespace App\Models\Contracts;

interface MultiLanguageInterface
{
    public static function getWithContent();

    public static function getById($id);

    // public static function getContentById($id, $languageId);

    public function updateItemContent($id, $data);
}
