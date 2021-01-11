<?php

namespace EinsUndEins\TransactionMailExtenderModule\Renderer;

use OxidEsales\EshopCommunity\Application\Model\Order;

class SchemaRenderer implements RendererInterface
{
    /**
     * {@inheritDoc}
     */
    public function render(Order $order): string
    {
        return '';
    }
}
