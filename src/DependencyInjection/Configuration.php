<?php

namespace Sip\ParserCommand\DependencyInjection;

use Sip\ParserCommand\ParserCommandBundle;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder(ParserCommandBundle::getConfigName());

        return $treeBuilder;
    }
}