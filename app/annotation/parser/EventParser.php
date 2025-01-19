<?php

namespace app\annotation\parser;

use LinFly\Annotation\Contracts\IAnnotationParser;
use support\Container;
use Webman\Event\Event;

class EventParser implements IAnnotationParser
{
    private static array $events = [];

    public static function process(array $item): void
    {
        self::$events[$item['parameters']['name']][] = [self::getCallable($item)];
    }

    public static function EventHandler(): void
    {
        foreach (static::$events as $name => $events) {
            // 支持排序，1 2 3 ... 9 a b c...z
            ksort($events, \SORT_NATURAL);
            foreach ($events as $callbacks) {
                foreach ($callbacks as $callback) {
                    Event::on($name, $callback);
                }
            }
        }
        self::recovery();
    }

    private static function getCallable($item): array
    {
        return [Container::get($item['class']), $item['method']];
    }

    protected static function recovery(): void
    {
        self::$events = [];
    }
}
