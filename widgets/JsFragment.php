<?php

namespace app\widgets;

use yii\web\View;
use yii\widgets\Block;


class JsFragment extends Block
{


    public $key = null;

    public $pos = View::POS_END;

    public function run()
    {
        $block = ob_get_clean();
        $block = trim($block);
        $jsFragPattern = '|^<script[^>]*>(?P<block_content>.+?)</script>$|is';
        if (preg_match($jsFragPattern, $block, $matches)) {
            $block = $matches['block_content'];
        }

        $this->view->registerJs($block, $this->pos, $this->key);
    }
}