<?php

namespace Sip\ParserCommand\MessageHandler;

use Sip\ParserCommand\Command\ParserCommand;
use Sip\ParserCommand\Message\ParserQueueMessage;
use Sip\ParserCommand\Service\Interfaces\SaveDataInterface;
use Sip\ParserCommand\Service\ReaderRedisStorage;
use Sip\ImageParser\Interfaces\ImageParserInterface;
use Sip\ReaderManager\Interfaces\ReaderManagerInterface;
use Sip\ReaderManager\ReaderManager;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Messenger\MessageBusInterface;

final class ParserQueueMessageHandler implements MessageHandlerInterface
{
    private MessageBusInterface $bus;
    private SaveDataInterface $saveData;

    public function __construct(MessageBusInterface $bus, SaveDataInterface $saveData)
    {
        $this->bus = $bus;
        $this->saveData = $saveData;
    }

    public function __invoke(ParserQueueMessage $message)
    {
        try {
            $readerStorage = ParserCommand::getStorage();
            $readerManager = new ReaderManager($readerStorage);

            $readerManager->setDeep($message->getDeep());
            $readerManager->setMaxDeep($message->getMaxDeep());
            $readerManager->setMaxPages($message->getMaxPages());
            $readerManager->setIsNoImageSubDomain($message->isNoImageSubDomain());
            $readerManager->setIsNoHrefSubDomain($message->isNoHrefSubDomain());

            $readUserFunction = function(
                ImageParserInterface   $parser,
                ReaderManagerInterface $readerManager,
                string $url,
                int $index
            ) use ($message, $readerStorage){
                 if ($index >= $message->getMaxPages()) {
                     return false;
                 }

                $this->bus->dispatch(
                    new ParserQueueMessage(
                        $url,
                        $readerManager->getDeep() + 1,
                        $message->getMaxDeep(),
                        $message->getMaxPages(),
                        $message->isNoHrefSubDomain(),
                        $message->isNoImageSubDomain()
                    )
                );
                return true;
            };

            $parserUserFunction = function (ImageParserInterface $parser) use ($message) {
                if ($message->isNoHrefSubDomain()) {
                    $parser->addTagValidator('href');
                }

                if ($message->isNoImageSubDomain()) {
                    $parser->deleteTagValidator('img');
                }
                return $parser;
            };

            $options = [
                'parser' => $parserUserFunction,
                'read' => $readUserFunction
            ];

            $parser = $readerManager->run($message->getUrl(), $options);

            if ($parser === null) {
                return;
            }
            $print = "read: ".$message->getUrl();
            $urlData = $readerStorage->getUrl($message->getUrl());

            $saveResult = $this->saveData->save(
                $urlData['url'],
                md5($urlData['url']),
                $urlData['executionTime'],
                $urlData['deep'],
                $parser->getImgLength()
            );

            if (strlen($print) >  100) {
                $print = substr($print, 0, 95) .' ... ';
            } else {
                $print .= str_repeat(' ', 100 - strlen($print));
            }

            if ($saveResult) {
                $status = "saved [\033[42m Ok \033[0m]";
            } else {
                $status = "saved [\033[41m No \033[0m]";
            }
            printf("%s %60s\n", $print, $status);
            echo "\n";
        } catch (\Throwable $exception) {
            var_dump($exception->getMessage());
        }
    }
}

