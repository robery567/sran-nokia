<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\DBALException;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190421170003 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Create device entity.';
    }

	/**
	 * @param Schema $schema
	 * @throws DBALException
	 */
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE device_entity (id INT AUTO_INCREMENT NOT NULL, sbts_id INT NOT NULL, sbts_status TINYINT(1) DEFAULT NULL, sbts_hw_configuration VARCHAR(255) DEFAULT NULL, sbts_sw_release VARCHAR(255) DEFAULT NULL, last_information_refresh DATETIME DEFAULT NULL, sbts_owner VARCHAR(255) NOT NULL, active_sw_version VARCHAR(255) DEFAULT NULL, passive_sw_version VARCHAR(255) DEFAULT NULL, sbts_state TINYINT(1) DEFAULT NULL, lte_state VARCHAR(255) DEFAULT NULL, wcdma_state VARCHAR(255) DEFAULT NULL, gsm_state VARCHAR(255) DEFAULT NULL, detected_hardware JSON DEFAULT NULL, connected_rf_modules JSON DEFAULT NULL, active_alarms LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\', ip_addresses JSON DEFAULT NULL, controllers JSON DEFAULT NULL, synchronization_source VARCHAR(255) DEFAULT NULL, synchronization_status VARCHAR(255) DEFAULT NULL, timesources JSON DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
    }

	/**
	 * @param Schema $schema
	 * @throws DBALException
	 */
    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE device_entity');
    }
}
