<?php
class ShohinCheckRirekiCookieView extends SgView
{
    /**
     * {@inheritdoc}
     */
    function  execute($controller, $request, $user)
    {
        $this->setTemplate(dirname(__DIR__) . '/templates/ShohinCheckRirekiCookie.tpl');
        $renderer = $this->_renderer;
        // 商品一覧データ
        $renderer->setAttribute('arrShohin', $request->getAttribute('arrShohin'));
        // ご利用可能ポイント
        $renderer->setAttribute('remainPoint', $request->getAttribute('remainPoint'));
        return $renderer;
    }
}