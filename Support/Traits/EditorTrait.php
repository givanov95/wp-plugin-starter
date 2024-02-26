<?php

namespace Plugin\Support\Traits;

trait EditorTrait
{
    public static function getEditor(): string
    {
        // Enqueue editor scripts and styles (already mentioned in previous response)

        ob_start(); // Start output buffering
        wp_editor('', 'product_description', array(
            'media_buttons' => false,
            'textarea_rows' => 10,
        ));
        $editorHtml = ob_get_clean(); // Capture buffered output

        return $editorHtml;
    }
}
