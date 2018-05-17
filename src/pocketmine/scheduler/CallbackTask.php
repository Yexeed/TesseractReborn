<?php declare(strict_types=1);
/**
 *  __     __                         _
 *  \ \   / /                        | |
 *   \ \_/ /___ __  __ ___   ___   __| |
 *    \   // _ \\ \/ // _ \ / _ \ / _` |
 *     | ||  __/ >  <|  __/|  __/| (_| |
 *     |_| \___|/_/\_\\___| \___| \__,_|
 *
 *           users are losers
 *               {2018}.
 */

namespace pocketmine\scheduler;

/**
 * Allows the creation of simple callbacks with extra data
 * The last parameter in the callback will be this object
 *
 * If you want to do a task in a Plugin, consider extending PluginTask to your needs
 *
 */
class CallbackTask extends Task{

    /** @var callable */
    protected $callable;

    /** @var array */
    protected $args;

    /**
     * @param callable $callable
     * @param array    $args
     */
    public function __construct(callable $callable, array $args = []){
        $this->callable = $callable;
        $this->args = $args;
    }

    /**
     * @return callable
     */
    public function getCallable() : callable{
        return $this->callable;
    }

    public function onRun(int $currentTicks){
        $c = $this->callable;
        $args = $this->args;
        $c(...$args);
    }

}