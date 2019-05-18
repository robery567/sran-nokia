<?php

namespace App\Repository;

use App\Entity\DeviceEntity;
use DateTimeInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method DeviceEntity|null find($id, $lockMode = null, $lockVersion = null)
 * @method DeviceEntity|null findOneBy(array $criteria, array $orderBy = null)
 * @method DeviceEntity[]    findAll()
 * @method DeviceEntity[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DeviceEntityRepository extends ServiceEntityRepository {

	public function __construct(RegistryInterface $registry) {
		parent::__construct($registry, DeviceEntity::class);
	}

	/**
	 * @param int $sbtsId
	 * @return DeviceEntity
	 * @throws NonUniqueResultException
	 */
	public function getBySbtsId(int $sbtsId): ?DeviceEntity {
		return $this->createQueryBuilder('device_entity')
			->andWhere('device_entity.sbtsId = :sbtsId')
			->setParameter('sbtsId', $sbtsId)
			->setMaxResults(1)
			->getQuery()
			->getOneOrNullResult();
	}

	/**
	 * @return DeviceEntity[]
	 */
	public function findAllOrderedBySbtsId(): array {
		return $this->createQueryBuilder('device_entity')
			->orderBy('device_entity.sbtsId', 'ASC')
			->getQuery()
			->getResult();
	}

	/**
	 * @param DateTimeInterface $dateTime
	 * @return DeviceEntity[]
	 */
	public function findDevicesThatNeedRefresh(DateTimeInterface $dateTime): array {
		return $this->createQueryBuilder('device_entity')
			->andWhere("TIME_FORMAT(device_entity.refreshTime ,'%H:%i') = :refreshTime")
			->setParameter('refreshTime', $dateTime->format('H:i'))
			->getQuery()
			->getResult();
	}

	/**
	 * @return DeviceEntity[]
	 */
	public function findDevicesThatUseTheDefaultRefreshTime(): array {
		return $this->createQueryBuilder('device_entity')
			->andWhere('device_entity.refreshTime IS NULL')
			->getQuery()
			->getResult();
	}
}
