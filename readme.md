# Nette Wires

> 💿 `composer require dakujem/nette-wires`


## SK / CS

Wire Genie umoznuje redukovat "boilerplate" kod suvisiaci s drotovanim sluzieb
cez prezentery (napriklad vytvaranie komponent
a niektorych jednorazovych sluzieb typu _builder_ alebo _factory_).


> Ale bacha! 🤚
>
> V zavislosti od konkretneho pouzitia moze dojst k poruseniu _IoC_ principov
> a degradacii _dependency injection_ principov na _service locator_.
> Pouzivajte na vlastnu zdpovednost, ak viete, co robite.
>
> Na druhej strane, ak vytiahujete sluzby z kontajneru,
> mozete uz radsej pouzit Wire Genie.


#### Instalacia

> 💿 `composer require dakujem/nette-wires`

Tento metapackage instaluje [Wire Genie](https://github.com/dakujem/wire-genie)
a navod nizsie odporuca instalaciu
[Contributte/PSR-11-kontajner](https://github.com/contributte/psr11-container-interface),
ale mozete pouzit lubovolny iny PSR-11 wrapper Nette DI kontajneru.


Pokial si nainstalujete `contributte/psr11-container-interface`,
mozete vo svojom bazovom prezenteri pouzit
[`WireGenieTrait`](src/WireGenieTrait.php),
ktory prida do prezenteru metodu `wire`.
> `composer require contributte/psr11-container-interface`

```php
namespace App\Presenters;

use Dakujem\WireGenieTrait;
use Nette;

abstract class BasePresenter extends Nette\Application\UI\Presenter
{
    use WireGenieTrait;
}
```
> 💡 Nepouzivajte _traity_, pokial nerozumiete, co s nimi nie je v poriadku.

Implementacia `wire` metody je inak vo vasich rukach.\
Namiesto nej mozete tiez priamo volat `$wireGenie->provide( ... )->invoke( ... )`.


#### Pouzitie

Metodu `wire` je mozne pouzit napriklad v `createComponent*` metodach:
```php
    protected function createComponentFoobarForm()
    {
        $factory = function (InputFactory $inputs, TextRepository $textRepo) {
            $form = new Form();
            $form->addComponent(
                $inputs->create('stuff', $textRepo->getAllUnread()),
                'unread_stuff'
            );
            // ...
            return $form;
        };
        return $this->wire(InputFactory::class, 'model.repository.text')->invoke($factory);
    }
```

Lepsie je zabalit kod do tovarne alebo populatoru (moznost testovat):
```php
    protected function createComponentFoobarForm()
    {
        return $this->wire(InputFactory::class, 'model.repository.text')
            ->invoke([new FoobarFormFactory, 'create']);
    }
```

Lokalne zavislosti z prezenteru je mozne pribalit cez anonymne funkcie:
```php
    protected function createComponentFoobarForm()
    {
        return $this->wire(InputFactory::class, 'model.repository.text')
            ->invoke(function (...$deps) {
                return (new FoobarFormFactory)->create(
                    $this->localDependency,
                    $this->getParameter('id'),
                    ...$deps
                );
            });
    }

```

Tento postup umoznuje vyhnut sa injektovaniu mnozstva zavislosti do prezenterov,
pokial nie su vzdy pouzivane
(prezetner moze mat viacero akcii, pouzije sa len jedna; komponenty detto).

Taketo pouzitie Wire Genie riesi okrajovy pripad, kedy vznika boilerplate.\
Pred pouzitim skuste pouvazovat, ci sa vas pripad neda vyriesit cistejsie.

Porovnajte vyhody a nevyhody:
- ❔ zachovanie IoC je otazne (zalezi na uhle pohladu)
- ➕ vyhodou je mala pracnost riesenia
- ➕ prehladne prepojenie zavislosti, jednoduche na pochopenie
- ➕ moznost konfiguracie drotovania zavislosti existuje
- ➕ testovatelnost je jednoduchsia ako v priapde tovarni vygenerovanych DI
- ➕ prezenter neriesi, odkial zavislosti tecu, ale _deklaruje_, ake sluzby sa maju nadrotovat
- ➕ lazy loading v momente realneho pouzitia
- ➖ ~~ziaden autowiring~~ autowiring je mozne jednoducho implementovat, vid [strucny navod v balicku](https://github.com/dakujem/wire-genie#automatic-dependency-resolution)
- ➖ „maskovany“ service lokator (❔)
- ➖ kontajner pri kompilacii nezisti problemy s chybajucimi alebo konfliktnymi sluzbami


> Alternativne mozete skusit iny kompromis, napr. [Kdyby/Autowired](https://github.com/Kdyby/Autowired).


## EN

Allows to fetch multiple dependencies from a DI container
and provide them as arguments to a callable.\
Metapackage.

> Disclaimer 🤚
>
> Depending on actual use, this might be breaking _IoC_
> and degrade your _dependency injection_ container to a _service locator_,
> so use it with caution.
>
> But then again, if you can `get` a service from your container, you can use wire genie.


🚧 To be done.


> 💡 Do not use _traits_ unless you understand what's wrong with them.

