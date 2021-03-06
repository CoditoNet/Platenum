<?php
declare(strict_types=1);
namespace Thunder\Platenum\Enum;

use Thunder\Platenum\Exception\PlatenumException;

/**
 * @author Tomasz Kowalczyk <tomasz@kowalczyk.cc>
 */
trait CallbackEnumTrait
{
    use EnumTrait;

    /** @var non-empty-array<class-string,callable():array<string,int|string>> */
    protected static $callbacks = [];

    /** @param callable():array<string,int|string> $callback */
    final public static function initialize(callable $callback): void
    {
        if(array_key_exists(static::class, static::$callbacks)) {
            throw PlatenumException::fromAlreadyInitializedCallback(static::class);
        }

        static::$callbacks[static::class] = $callback;
    }

    final private static function resolve(): array
    {
        if(false === array_key_exists(static::class, static::$callbacks)) {
            throw PlatenumException::fromInvalidCallback(static::class);
        }
        if(false === is_callable(static::$callbacks[static::class])) {
            throw PlatenumException::fromInvalidCallback(static::class);
        }

        return (static::$callbacks[static::class])();
    }
}
