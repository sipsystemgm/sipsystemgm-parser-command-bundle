<?php

namespace Sip\ParserCommand\Command;

use Sip\ParserCommand\Message\ParserQueueMessage;
use Sip\ParserCommand\Service\MemcachedStorage;
use Sip\ParserCommand\Service\ReaderRedisStorage;
use Predis\Client;
use Sip\ReaderManager\Interfaces\ReaderStorageInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Messenger\MessageBusInterface;

class ParserCommand extends Command
{
    protected static $defaultName = 'parser';
    protected static $defaultDescription = 'Image parser';
    private MessageBusInterface $bus;

    public function __construct(MessageBusInterface $bus, string $name = null)
    {
        $this->bus = $bus;
        parent::__construct($name);
    }

    protected function configure(): void
    {
        $this
            ->addArgument('url', InputArgument::REQUIRED, 'url')
            ->addArgument('deep', InputArgument::OPTIONAL, 'deep')
            ->addArgument('max-page', InputArgument::OPTIONAL, 'max-page')
            ->addOption('no-img-subdomain', null, InputOption::VALUE_NONE)
            ->addOption('no-href-subdomain', null, InputOption::VALUE_NONE)
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $storage = self::getStorage();
        $storage->clear();
        $io = new SymfonyStyle($input, $output);
        $url = $input->getArgument('url');
        $maxDeep = $input->getArgument('deep');
        $maxPage = $input->getArgument('max-page');
        $isNoHrefSubDomain = $input->getOption('no-href-subdomain');
        $isNoImageSubDomain = $input->getOption('no-img-subdomain');

        if (empty($maxDeep)) {
            $maxDeep = 3;
        }

        if (empty($maxPage)) {
            $maxPage = 10;
        }

        $this->bus->dispatch(new ParserQueueMessage(
            $url,
            1,
            $maxDeep,
            $maxPage,
            $isNoHrefSubDomain,
            $isNoImageSubDomain
        ));
        return Command::SUCCESS;
    }

    public static function getStorage(): ReaderStorageInterface
    {
        return new MemcachedStorage('data_storage', [
            'host' => $_ENV['MEMCACHED_HOST'],
            'port' => $_ENV['MEMCACHED_PORT']
        ]);
    }
}

