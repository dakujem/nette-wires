<?php

namespace Dakujem;

use Contributte\Psr11\Container as Psr11Container;
use Nette\DI\Container as NetteContainer;

/**
 * A trait to add to your BasePresenter, mostly for lazy people.
 *
 * Using this trait assumes you have installed `contributte/psr11-container-interface` package:
 *    $ composer require contributte/psr11-container-interface
 *
 * @author Andrej Rypák (dakujem) <xrypak@gmail.com>
 */
trait WireGenieTrait
{
    /** @var WireGenie */
    private $wireGenie;

    /**
     * Fetch & wire dependencies.
     *
     * Usage:
     *   $factoryFunction = function( ...dependencies... ){
     *       // do stuff or create stuff
     *       return new Service( ... );
     *   };
     *   $service = $this->wire( ...dependency-identifier-list... )->invoke($factoryFunction);
     *
     * @param mixed ...$args
     * @return InvokableProvider
     */
    protected function wire(...$args): callable
    {
        return $this->wireGenie->provide(...$args);
    }

    public function injectWireGenie(NetteContainer $dic): void
    {
        // The following gives the genie access to the whole DI container.
        $this->wireGenie = new WireGenie(new Psr11Container($dic));
    }
}
