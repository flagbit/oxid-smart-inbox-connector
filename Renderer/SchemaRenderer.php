<?php

namespace EinsUndEins\TransactionMailExtenderModule\Renderer;

use EinsUndEins\SchemaOrgMailBody\Model\Order as SchemaOrder;
use EinsUndEins\SchemaOrgMailBody\Model\ParcelDelivery;
use EinsUndEins\SchemaOrgMailBody\Renderer\OrderRenderer;
use EinsUndEins\SchemaOrgMailBody\Renderer\ParcelDeliveryRenderer;
use EinsUndEins\TransactionMailExtenderModule\Database\Shop;
use EinsUndEins\TransactionMailExtenderModule\Status\MappingInterface;
use OxidEsales\EshopCommunity\Application\Model\DeliverySet;
use OxidEsales\EshopCommunity\Application\Model\Order;

class SchemaRenderer implements RendererInterface
{
    /**
     * @var Shop
     */
    private $shopDbTable;

    /**
     * @var MappingInterface
     */
    private $statusMapping;

    public function __construct(Shop $shopDbTable, MappingInterface $statusMapping)
    {
        $this->shopDbTable = $shopDbTable;
        $this->statusMapping = $statusMapping;
    }

    /**
     * {@inheritDoc}
     */
    public function render(Order $order): string
    {
        $schemaOrder = $this->oxidOrderToSchemaOrder($order);
        $result = (new OrderRenderer($schemaOrder))->render();

        /** @var DeliverySet $deliverSet */
        $deliverSet = $order->getDelSet();

        // Despite the return type is string in $order->getTrackCode(), null is still possible
        $schemaDelivery = $this->oxidDeliveryToSchemaDelivery($deliverSet, $schemaOrder, (string) $order->getTrackCode());
        $result .= (new ParcelDeliveryRenderer($schemaDelivery))->render();

        return $result;
    }

    private function oxidOrderToSchemaOrder(Order $order): SchemaOrder
    {
        $status = $this->statusMapping->getValueBy($order);

        $shopName = $this->shopDbTable->fetchNameById($order->getShopId());
        $orderNumber = $order->getFieldData('oxorder__oxordernr');

        return new SchemaOrder($orderNumber, $status, $shopName);
    }

    private function oxidDeliveryToSchemaDelivery(DeliverySet $deliverySet, SchemaOrder $order, string $trackingCode): ParcelDelivery
    {
        $deliveryName = $deliverySet->getFieldData('oxdeliveryset__oxtitle');

        return new ParcelDelivery($deliveryName, $trackingCode, $order->getOrderNumber(), $order->getOrderStatus(), $order->getShopName());
    }
}
