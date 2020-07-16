<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200716032320 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('CREATE TABLE checklist (id CHAR(36) NOT NULL --(DC2Type:uuid)
        , created_by_id INTEGER NOT NULL, template_id CHAR(36) NOT NULL --(DC2Type:uuid)
        , description VARCHAR(1024) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_5C696D2FB03A8386 ON checklist (created_by_id)');
        $this->addSql('CREATE INDEX IDX_5C696D2F5DA0FB8 ON checklist (template_id)');
        $this->addSql('CREATE TABLE checklist_entry (id CHAR(36) NOT NULL --(DC2Type:uuid)
        , checklist_item_id CHAR(36) NOT NULL --(DC2Type:uuid)
        , user_id INTEGER NOT NULL, value TEXT CHECK(value IN (\'not-set\', \'na\', \'checked\')) NOT NULL --(DC2Type:ItemStatus)
        , date_time_created DATETIME NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_15C91AAE7E0892A4 ON checklist_entry (checklist_item_id)');
        $this->addSql('CREATE INDEX IDX_15C91AAEA76ED395 ON checklist_entry (user_id)');
        $this->addSql('CREATE TABLE checklist_item (id CHAR(36) NOT NULL --(DC2Type:uuid)
        , checklist_item_group_id CHAR(36) NOT NULL --(DC2Type:uuid)
        , name VARCHAR(255) NOT NULL, sort_order INTEGER NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_99EB20F928793ED6 ON checklist_item (checklist_item_group_id)');
        $this->addSql('CREATE TABLE checklist_item_group (id CHAR(36) NOT NULL --(DC2Type:uuid)
        , checklist_id CHAR(36) NOT NULL --(DC2Type:uuid)
        , name VARCHAR(255) NOT NULL, sort_order INTEGER NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_E7188ACAB16D08A7 ON checklist_item_group (checklist_id)');
        $this->addSql('CREATE TABLE checklist_template (id CHAR(36) NOT NULL --(DC2Type:uuid)
        , name VARCHAR(255) NOT NULL, template_file VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE user (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, email VARCHAR(255) NOT NULL, roles CLOB NOT NULL --(DC2Type:json)
        , password VARCHAR(255) NOT NULL)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('DROP TABLE checklist');
        $this->addSql('DROP TABLE checklist_entry');
        $this->addSql('DROP TABLE checklist_item');
        $this->addSql('DROP TABLE checklist_item_group');
        $this->addSql('DROP TABLE checklist_template');
        $this->addSql('DROP TABLE user');
    }
}
