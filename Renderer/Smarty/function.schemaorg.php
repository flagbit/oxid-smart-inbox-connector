<?php

use OxidEsales\EshopCommunity\Internal\Container\ContainerFactory;

/**
 * @param array<mixed> $params
 * @param Smarty $smarty
 *
 * @return string
 */
function smarty_function_schemaorg($params, &$smarty): string {
    if (! isset($params['order'])) {
        throw new LogicException('Missing function argument "order"');
    }

    if (! isset($params['shop'])) {
        throw new LogicException('Missing function argument "shop"');
    }

    $renderer = ContainerFactory::getInstance()
        ->getContainer()
        ->get('einsundeins.oxid_transaction_mail_extender.renderer.schema_renderer');

    return $renderer->render($params['order'], $params['shop']);
}
