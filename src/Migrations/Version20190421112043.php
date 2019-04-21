<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190421112043 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Added indexes for user_entity.';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE INDEX user_entity__is_admin_idx ON user_entity (is_admin)');
        $this->addSql('CREATE UNIQUE INDEX user_entity__email_idx ON user_entity (email)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP INDEX user_entity__is_admin_idx ON user_entity');
        $this->addSql('DROP INDEX user_entity__email_idx ON user_entity');
    }
}
