# Image Parser
## This is a parser command bundle.The functionality is implemented here to run parse command and run queue manager
This component was developed to demonstrate  developing approaches only!!! 

# Installation

```ssh
composer require sipsystemgm/parser-command
```
```php
// config/bundles.php

return [
    Symfony\Bundle\FrameworkBundle\FrameworkBundle::class => ['all' => true],
    ...
    // Insert bundle over there
    Sip\ParserCommand\ParserCommandBundle::class => ['all' => true]
]
```

# Configuration

```env
# config/packeges/messager.yaml
framework:
    messenger:
        transports:
             # insert parameter
             parser: '%env(MESSENGER_TRANSPORT_DSN)%'
        routing:
            # insert parameter
            'Sip\ParserCommand\Message\ParserQueueMessage': parser

```

```env

# .env
###> symfony/messenger ###
# Choose one of the transports below
...
# uncomment this page and set you parameters
# MESSENGER_TRANSPORT_DSN=redis://localhost:6379/messages
###< symfony/messenger ###

# insert this block and set your parameters
##> memcached ###
MEMCACHED_HOST=localhost
MEMCACHED_PORT=11211
##> memcached ###
```
