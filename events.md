# **Creacion de eventos**

Para poder trabajar con eventos es necesario tener en cuenta 3 cosas :

  * Definir evento
  * Crear el oyente del evento
  * Emitir el evento


## Definir evento ##

En esta caso se creara evento de tipo de confirmacion de orden de compra. Por lo general en el evento se debe enviar los datos que se pueden utilzar en la ejecucion del evento.

**OrderConfirm.php**
```
#!php
class OrderConfirm implements DomainEvent
{
    protected $orderId;
    protected $userId;

    public function __construct($orderId, $userId) {
        $this->orderId = $orderId;
        $this->userId  = $userId;
    }
}
```

## Definir Oyente Evento ##


Luego de haber definido el evento, se procede a crear oyente del evento, que tendra logica dependiendo de lo que se quiera hacer.


**OrderConfirmEvent.php**


```
#!php

Class OrderComfirmEvent  implement DomainEventSubscriber
{
    public function __construct()
    {
        ....
    }

   public function handle()
   {
        /// Logica de evento
   }

   public function isSubscribedTo($event)
   {
      return $event instanceOf OrderConfirm;
   }

}
```

**events.yml** (Configuracion exclusiva de eventos)

```



```


## Emitir Evento ##

```
#!php


function confirmOrder($data) {

     // validaciones de servicio
     // Logica de servicio
     // persistencia

    DomainEventPublisher::instance()->publish(new OrderConfirm($orderId, $userId));
}
```
