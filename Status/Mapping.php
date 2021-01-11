<?php

namespace EinsUndEins\TransactionMailExtenderModule\Status;

use OxidEsales\EshopCommunity\Application\Model\Order;

class Mapping implements MappingInterface
{
    public function getValueBy(Order $order): string
    {
        // NOT_FINISHED, OK, ERROR
        $status = $order->getFieldData('oxorder__oxtransstatus');
        $sendDate = $order->getFieldData('oxorder__oxsenddate');
        $paidDate = $order->getFieldData('oxorder__oxpaid');
        $storno = $order->getFieldData('oxorder__oxstorno');
        // ORDERFOLDER_NEW, ORDERFOLDER_FINISHED, ORDERFOLDER_PROBLEMS
        $folder = $order->getFieldData('oxorder__oxfolder');

        if ($status === 'NOT_FINISHED') {
            return 'OrderProcessing';
        }

        if ($status === 'ERROR' || $folder === 'ORDERFOLDER_PROBLEMS') {
            return 'OrderProblem';
        }

        // TODO Check articles ($order->getOrderArticles()) for individual status informations

        if ($storno !== 0) {
            return 'OrderCancelled';
        }

        if ($folder === 'ORDERFOLDER_FINISHED' && $sendDate !== null && $paidDate !== null) {
            return 'OrderDelivered';
        }

        if ($paidDate === null && $sendDate !== null) {
            return 'OrderPaymentDue';
        }

        if ($sendDate !== null) {
            return 'OrderInTransit';
        }

        return 'OrderProcessing';
    }
}
