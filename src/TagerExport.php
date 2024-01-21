<?php

namespace OZiTAG\Tager\Backend\Export;

use OZiTAG\Tager\Backend\Export\Contracts\BaseExportStrategy;

class TagerExport
{
    private static array $strategies = [];

    private static ?string $fileScenario = null;

    public static function init(string $fileScenario)
    {
        self::$fileScenario = $fileScenario;
    }

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
        $strategy = self::$strategies[$strategyId] ?? null;
        if(!$strategy){
            return null;
        }

        $strategy->reset();
        return $strategy;
    }

    /**
     * @return BaseExportStrategy[]
     */
    public static function getStrategies(): array
    {
        return self::$strategies;
    }

    public static function getFileScenario(): ?string
    {
        return self::$fileScenario;
    }
}
