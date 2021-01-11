<?php

namespace EinsUndEins\TransactionMailExtenderModule\Tests\Status;

use EinsUndEins\TransactionMailExtenderModule\Status\Mapping;
use OxidEsales\EshopCommunity\Application\Model\Order;
use PHPUnit\Framework\TestCase;

class MappingTest extends TestCase
{
    /**
     * @dataProvider provideStatus
     */
    public function testGetValueBy(string $oxidStatus, string $schemaStatus, ?string $sendDate, ?string $paidDate, ?int $storno, string $folder): void
    {
        $statusMapping = new Mapping();

        $order = $this->createMock(Order::class);
        $order->method('getFieldData')
            ->willReturnMap([
                ['oxorder__oxtransstatus', $oxidStatus],
                ['oxorder__oxsenddate', $sendDate],
                ['oxorder__oxpaid', $paidDate],
                ['oxorder__oxstorno', $storno],
                ['oxorder__oxfolder', $folder],
            ]);

        self::assertSame($schemaStatus, $statusMapping->getValueBy($order));
    }

    public function provideStatus(): array
    {
        return [
            'Not Finished' => ['NOT_FINISHED', 'OrderProcessing', '2020-10-10', '2020-10-10', 0, 'ORDERFOLDER_NEW'],
            'ERROR in status' => ['ERROR', 'OrderProblem', '2020-10-10', '2020-10-10', 0, 'ORDERFOLDER_NEW'],
            'ERROR in folder' => ['OK', 'OrderProblem', '2020-10-10', '2020-10-10', 0, 'ORDERFOLDER_PROBLEMS'],
            'Finished' => ['OK', 'OrderDelivered', '2020-10-10', '2020-10-10', 0, 'ORDERFOLDER_FINISHED'],
            'Payment due and in delivery' => ['OK', 'OrderPaymentDue', '2020-10-10', null, 0, 'ORDERFOLDER_NEW'],
            'Paid but not in delivery' => ['OK', 'OrderProcessing', null, '2020-10-10', 0, 'ORDERFOLDER_NEW'],
            'Paid and in delivery' => ['OK', 'OrderInTransit', '2020-10-10', '2020-10-10', 0, 'ORDERFOLDER_NEW'],
            'Storno' => ['OK', 'OrderCancelled', '2020-10-10', '2020-10-10', 1, 'ORDERFOLDER_FINISHED'],
            'Fresh order' => ['OK', 'OrderProcessing', null, null, 0, 'ORDERFOLDER_NEW'],
        ];
    }
}
