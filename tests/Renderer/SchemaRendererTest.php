<?php

namespace EinsUndEins\TransactionMailExtenderModule\Tests\Renderer;

use EinsUndEins\TransactionMailExtenderModule\Renderer\SchemaRenderer;
use EinsUndEins\TransactionMailExtenderModule\Status\MappingInterface;
use OxidEsales\EshopCommunity\Application\Model\DeliverySet;
use OxidEsales\EshopCommunity\Application\Model\Order;
use OxidEsales\EshopCommunity\Application\Model\Shop;
use PHPUnit\Framework\TestCase;

class SchemaRendererTest extends TestCase
{
    public function testRenderOrderDelivery(): void
    {
        $delivery = $this->createMock(DeliverySet::class);
        $delivery->method('getFieldData')
            ->with('oxdeliveryset__oxtitle')
            ->willReturn('UPS');

        $order = $this->createMock(Order::class);
        $order->method('getFieldData')
            ->willReturnMap([
                ['oxorder__oxordernr', '42'],
            ]);
        $order->method('getShopId')
            ->willReturn(1);
        $order->method('getTrackCode')
            ->willReturn('trackingcode');
        $order->method('getDelSet')
            ->willReturn($delivery);

        $shop = $this->createMock(Shop::class);
        $shop->expects(self::once())
            ->method('getFieldData')
            ->with('oxshops__oxname')
            ->willReturn('shopName');

        $statusMapping = $this->createMock(MappingInterface::class);
        $statusMapping->method('getValueBy')
            ->with($order)
            ->willReturn('OrderProblem');

        $schemaRenderer = new SchemaRenderer($statusMapping);

        $expected = <<<DOC
<div itemscope itemtype="http://schema.org/Order">
    <div itemprop="merchant" itemscope itemtype="http://schema.org/Organization">
    <meta itemprop="name" content="shopName"/>
</div>
    <meta itemprop="orderNumber" content="42"/>
    <link itemprop="orderStatus" href="http://schema.org/OrderProblem"/>
</div><div itemscope itemtype="http://schema.org/ParcelDelivery">
    <div itemprop="carrier" itemscope itemtype="http://schema.org/Organization">
    <meta itemprop="name" content="UPS"/>
</div>
    <meta itemprop="trackingNumber" content="trackingcode"/>
    <div itemprop="partOfOrder" itemscope itemtype="http://schema.org/Order">
        <div itemprop="merchant" itemscope itemtype="http://schema.org/Organization">
    <meta itemprop="name" content="shopName"/>
</div>
    </div>
    <meta itemprop="orderNumber" content="42"/>
    <link itemprop="orderStatus" href="http://schema.org/OrderProblem"/>
</div>
DOC;

        self::assertSame($expected, $schemaRenderer->render($order, $shop));
    }
}
