<?php

namespace EinsUndEins\TransactionMailExtenderModule\Tests\Database;

use Doctrine\DBAL\Driver\Result;
use Doctrine\DBAL\Query\QueryBuilder;
use EinsUndEins\TransactionMailExtenderModule\Database\Shop;
use LogicException;
use OxidEsales\EshopCommunity\Internal\Framework\Database\QueryBuilderFactoryInterface;
use PHPUnit\Framework\TestCase;

class ShopTest extends TestCase
{
    /**
     * @var QueryBuilder
     */
    private $queryBuilder;

    /**
     * @var Result
     */
    private $queryBuilderResult;

    /**
     * @var QueryBuilderFactoryInterface
     */
    private $queryBuilderFactory;

    protected function setUp(): void
    {
        $this->queryBuilder = $this->createMock(QueryBuilder::class);
        $this->queryBuilderFactory = $this->createMock(QueryBuilderFactoryInterface::class);
        $this->queryBuilderResult = $this->createMock(Result::class);

        $this->queryBuilderFactory->expects(self::once())
            ->method('create')
            ->willReturn($this->queryBuilder);

        parent::setUp();
    }

    public function testFetchNameById(): void
    {
        $shopDbTable = new Shop($this->queryBuilderFactory);

        $this->queryBuilder->method('select')->with('oxname')->willReturnSelf();
        $this->queryBuilder->method('from')->willReturnSelf();
        $this->queryBuilder->method('where')->with('oxid = :id')->willReturnSelf();
        $this->queryBuilder->method('setParameter')->with('id', 1)->willReturnSelf();
        $this->queryBuilder->method('execute')->willReturn($this->queryBuilderResult);

        $this->queryBuilderResult
            ->expects(self::once())
            ->method('fetchOne')
            ->willReturn('shopname');

        self::assertSame('shopname', $shopDbTable->fetchNameById(1));
    }

    public function testFetchNameByIdShopNotFound(): void
    {
        $shopDbTable = new Shop($this->queryBuilderFactory);

        $this->queryBuilder->method('select')->with('oxname')->willReturnSelf();
        $this->queryBuilder->method('from')->willReturnSelf();
        $this->queryBuilder->method('where')->with('oxid = :id')->willReturnSelf();
        $this->queryBuilder->method('setParameter')->with('id', 1)->willReturnSelf();
        $this->queryBuilder->method('execute')->willReturn($this->queryBuilderResult);

        $this->queryBuilderResult
            ->expects(self::once())
            ->method('fetchOne')
            ->willReturn(false);

        $this->expectException(LogicException::class);
        $this->expectExceptionMessage('Shop id "1" not found');
        self::assertSame('shopname', $shopDbTable->fetchNameById(1));
    }
}
