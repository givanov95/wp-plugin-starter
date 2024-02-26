<?php

namespace Plugin\Admin\Views\Products;

use Plugin\Support\Traits\EditorTrait;
use Plugin\Support\Views\View;

class CreateView extends View
{
    use EditorTrait;

    public static function render()
    {

        $wpEditor = self::getEditor();

        $html = <<<HTML
            <div class="wrap">
                <h1>Custom Plugin</h1>
                <form action="#" method="post">
                    <div class="flex justify-between">
            
                        <div class="col left-col w-75" style="width: 75%">
            
                            <h4 for="product-name">Product Name</h4>
                            <input type="text" id="product-name" name="product_name" class="regular-text">
            
                            <h4 for="product-description">Product Description</h4>
                            <div class="wp-editor-wrap">
                                $wpEditor
                            </div>
                        </div>
            
                        <div class="col right-col aside">
                            <div class="form-field form-required term-image-wrap">
                                <label for="product-image">Product Image</label>

                                <div class="form-field form-required term-preview-wrap bg-white margin-y padding">
                                    <label for="product-preview">Product Image Preview</label>
                                    <div class="product-image-preview">
                                        <input type="file" id="product-image" name="product_image" class="regular-text">
                                    </div>

                                   
                                </div>

                                <div class="bg-white padding">
                                        <button name="submit" class="button button-primary button-large">Send</button>
                                    </div>
                            </div>
                        </div>            
                    </div>

               
                </form>
            </div>
        HTML;

        echo $html;
    }


}
