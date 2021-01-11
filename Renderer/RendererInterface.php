<?php

namespace EinsUndEins\TransactionMailExtenderModule\Renderer;

use OxidEsales\EshopCommunity\Application\Model\Order;

interface RendererInterface
{
    /**
     * Renders a Schema.org conform HTML.
     */
    public function render(Order $order): string;
}
