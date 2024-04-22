<?php
class ShukaJyokyoView extends SgView
{
    /**
     * @var string
     */
    protected $_title = '交換履歴一覧';

    /**
     * {@inheritdoc}
     */
    function execute($controller, $request, $user)
    {
        $this->setTemplate(dirname(__DIR__) . '/templates/ShukaJyokyo.tpl');
        $renderer = $this->_renderer;
        $renderer->setAttribute('show_result', $request->hasAttribute('hassoKensu'));
        // 発送件数
        $renderer->setAttribute('hassoKensu', $request->getAttribute('hassoKensu'));
        if (strcmp($request->getAttribute('hassoKensu'), '0') !== 0) {
            // 発送情報取得
            $renderer->setAttribute('hassoJyokyoInfo', $request->getAttribute('hassoJyokyoInfo'));
        }
        return $renderer;
    }
}
