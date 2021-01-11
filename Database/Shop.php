<?php

namespace EinsUndEins\TransactionMailExtenderModule\Database;

use OxidEsales\EshopCommunity\Internal\Framework\Database\QueryBuilderFactoryInterface;

class Shop
{
    private const TABLENAME = 'oxshops';

    /**
     * @var QueryBuilderFactoryInterface
     */
    private $queryBuilderFactory;

    public function __construct(QueryBuilderFactoryInterface $queryBuilderFactory)
    {
        $this->queryBuilderFactory = $queryBuilderFactory;
    }

    public function fetchNameById(int $id): string
    {
        $queryBuilder = $this->queryBuilderFactory->create();

        $queryBuilder->select('oxname')
            ->from(self::TABLENAME)
            ->where('oxid = :id')
            ->setParameter('id', $id);

        /** @var \Doctrine\DBAL\Driver\Result $statement */
        $statement = $queryBuilder->execute();

        $shopName = $statement->fetchOne();

        if ($shopName === false) {
            throw new \LogicException(sprintf('Shop id "%d" not found', $id));
        }

        return $shopName;
    }
}
