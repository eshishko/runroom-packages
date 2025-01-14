<?php

declare(strict_types=1);

/*
 * This file is part of the Runroom package.
 *
 * (c) Runroom <runroom@runroom.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Runroom\SeoBundle\Tests\Unit;

use PHPUnit\Framework\MockObject\Stub;
use PHPUnit\Framework\TestCase;
use Runroom\SeoBundle\Factory\MetaInformationFactory;
use Runroom\SeoBundle\MetaInformation\MetaInformationBuilder;
use Runroom\SeoBundle\Repository\MetaInformationRepository;
use Runroom\SeoBundle\Tests\App\Entity\Media;
use Runroom\SeoBundle\Tests\App\MetaInformation\TestMetaInformationProvider;
use Runroom\SeoBundle\Tests\App\ViewModel\DummyViewModel;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Zenstruck\Foundry\Test\Factories;

class MetaInformationBuilderTest extends TestCase
{
    use Factories;

    /**
     * @var Stub&MetaInformationRepository
     */
    private $repository;

    private MetaInformationBuilder $builder;

    protected function setUp(): void
    {
        $this->repository = $this->createStub(MetaInformationRepository::class);

        $this->builder = new MetaInformationBuilder(
            $this->repository,
            PropertyAccess::createPropertyAccessor()
        );
    }

    /**
     * @test
     */
    public function itBuildsMetaInformationViewModel(): void
    {
        $media = new Media();

        $metaInformation = MetaInformationFactory::new([
            'image' => $media,
        ])->withTranslations(['en'], [
            'title' => '[model.placeholder] title',
            'description' => '[model.missing] description',
        ])->create()->object();

        $this->repository->method('findOneBy')->willReturn($metaInformation);

        $metas = $this->builder->build(
            new TestMetaInformationProvider(),
            ['model' => new DummyViewModel()],
            'test'
        );

        static::assertSame('test title', $metas->getTitle());
        static::assertSame(' description', $metas->getDescription());
        static::assertSame($media, $metas->getImage());
    }
}
