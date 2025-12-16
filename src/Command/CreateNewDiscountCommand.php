<?php

declare(strict_types=1);

namespace App\Command;

use App\Entity\Product\Discount;
use App\Enum\FeatureFlag;
use App\Repository\TagRepository;
use App\Service\FeatureFlagService;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Contracts\Cache\TagAwareCacheInterface;

#[AsCommand(
    name: 'app:create-new-discount',
    description: 'Creates a new discount',
)]
class CreateNewDiscountCommand extends Command
{
    public function __construct(
        private readonly TagRepository $tagRepository,
        private readonly EntityManagerInterface $entityManager,
        private readonly TagAwareCacheInterface $cache,
        private readonly FeatureFlagService $featureFlagService,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addOption('title', null, InputOption::VALUE_REQUIRED, 'The discount title')
            ->addOption('description', null, InputOption::VALUE_REQUIRED, 'The discount description')
            ->addOption('percentage', null, InputOption::VALUE_REQUIRED, 'The discount percentage')
            ->addOption('startedAt', null, InputOption::VALUE_REQUIRED, 'The discount start date')
            ->addOption('endedAt', null, InputOption::VALUE_REQUIRED, 'The discount end date')
            ->addOption('tags', 't', InputOption::VALUE_IS_ARRAY | InputOption::VALUE_REQUIRED, 'The discount tags')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $discount = new Discount(
            title: $input->getOption('title'),
            description: $input->getOption('description'),
            tags: new ArrayCollection(),
            percentage: intval($input->getOption('percentage')),
            minimumPrice: null,
            startedAt: new \DateTimeImmutable($input->getOption('startedAt')),
            endedAt: new \DateTimeImmutable($input->getOption('endedAt')),
            createdAt: new \DateTimeImmutable(),
            updatedAt: new \DateTimeImmutable(),
        );

        $tags = $input->getOption('tags');

        if (empty($tags)) {
            $io->warning('Without any configured tags, your discount will not apply to products');
        }

        foreach ($tags as $tagSlug) {
            $tagEntity = $this->tagRepository->findOneBy(['slug' => $tagSlug])
                ?? throw new \InvalidArgumentException(sprintf('Tag "%s" not found', $tagSlug));

            $discount->tags->add($tagEntity);
        }

        $this->entityManager->persist($discount);
        $this->entityManager->flush();

        if ($this->featureFlagService->isEnabled(FeatureFlag::EnableApplicativeCache)) {
            $this->cache->invalidateTags(['product_model_data']);
        }

        $io->success("Successfully created discount \"{$discount->title}\"");

        return self::SUCCESS;
    }
}