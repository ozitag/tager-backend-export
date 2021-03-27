<?php

namespace OZiTAG\Tager\Backend\Export;

use OZiTAG\Tager\Backend\Export\Contracts\BaseExportStrategy;

class TagerExport
{
    private static array $strategies = [];

    public static function registerStrategy(string $stategyClassName)
    {
        /** @var BaseExportStrategy $strategy */
        $strategy = new $stategyClassName;

        if (is_subclass_of($stategyClassName, BaseExportStrategy::class) == false) {
            throw new \Exception($stategyClassName . ' is not a subclass of BaseExportStrategy');
        }

        self::$strategies[$strategy->getId()] = $strategy;
    }

    public static function getStrategy(string $strategyId): ?BaseExportStrategy
    {
        return self::$strategies[$strategyId] ?? null;
    }

    /**
     * @return BaseExportStrategy[]
     */
    public static function getStrategies(): array
    {
        return self::$strategies;
    }
}
