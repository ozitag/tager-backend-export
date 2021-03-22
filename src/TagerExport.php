<?php

namespace OZiTAG\Tager\Backend\Export;

use OZiTAG\Tager\Backend\Export\Contracts\BaseStrategy;

class TagerExport
{
    private static array $strategies = [];

    public static function registerStrategy(string $stategyClassName)
    {
        /** @var BaseStrategy $strategy */
        $strategy = new $stategyClassName;

        if (is_subclass_of($stategyClassName, BaseStrategy::class) == false) {
            throw new \Exception($stategyClassName . ' is not a subclass of BaseStrategy');
        }

        self::$strategies[$strategy->getId()] = $strategy;
    }

    public static function getStrategy(string $strategyId): ?BaseStrategy
    {
        return self::$strategies[$strategyId] ?? null;
    }

    /**
     * @return BaseStrategy[]
     */
    public static function getStrategies(): array
    {
        return self::$strategies;
    }
}
