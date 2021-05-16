<?php

namespace App\Command;

use App\Repository\ItemRepository;
use App\Service\EncryptionServiceInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class UpdateUnsecureItemDataCommand extends Command
{
    protected static $defaultName = 'app:update-unsecure-item-data';

    /**
     * @var ItemRepository
     */
    private $itemRepository;

    /**
     * @var EncryptionServiceInterface
     */
    private $encryptionService;

    public function __construct(ItemRepository $itemRepository, EncryptionServiceInterface $encryptionService)
    {
        $this->itemRepository = $itemRepository;
        $this->encryptionService = $encryptionService;

        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setDescription('This command encrypts all items that are not ecrypted (all items that have `encryption_service_name` field null)')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $unecryptedItems = $this->itemRepository->getAllUnencryptedItems();
        $affectedItems = 0;

        foreach ($unecryptedItems as $item) {
            $item->setData($this->encryptionService->encrypt($item->getData()));
            $item->setEncryptionServiceName($this->encryptionService->getServiceName());
            $this->itemRepository->save($item);
            $affectedItems++;
        }

        $io->success(sprintf(
            '%s items have been encrypted using `%s` encrypter',
            $affectedItems,
            $this->encryptionService->getServiceName()
        ));

        return Command::SUCCESS;
    }
}
