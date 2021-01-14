<?php

namespace EinsUndEins\TransactionMailExtenderModule\Renderer;

use OxidEsales\EshopCommunity\Application\Model\Order;
use OxidEsales\EshopCommunity\Application\Model\Shop;

interface RendererInterface
{
    /**
     * Renders a Schema.org conform HTML.
     */
    public function render(Order $order, Shop $shop): string;
}
