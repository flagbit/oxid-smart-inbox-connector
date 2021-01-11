<?php

namespace EinsUndEins\TransactionMailExtenderModule\Status;

use OxidEsales\EshopCommunity\Application\Model\Order;

interface MappingInterface
{
    public function getValueBy(Order $order): string;
}
