<?php

namespace JiguangSmsBundle\Tests\Integration\Repository;

use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use JiguangSmsBundle\Entity\VoiceCode;
use JiguangSmsBundle\Repository\VoiceCodeRepository;
use PHPUnit\Framework\TestCase;

class VoiceCodeRepositoryTest extends TestCase
{
    private VoiceCodeRepository $repository;
    private ManagerRegistry $registry;

    protected function setUp(): void
    {
        $this->registry = $this->createMock(ManagerRegistry::class);
        $this->repository = new VoiceCodeRepository($this->registry);
    }

    public function testConstruct(): void
    {
        $this->assertInstanceOf(VoiceCodeRepository::class, $this->repository);
    }

}