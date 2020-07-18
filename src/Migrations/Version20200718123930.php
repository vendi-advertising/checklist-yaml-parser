<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200718123930 extends AbstractMigration
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
        , created_by_id CHAR(36) NOT NULL --(DC2Type:uuid)
        , template_id CHAR(36) NOT NULL --(DC2Type:uuid)
        , description VARCHAR(1024) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_5C696D2FB03A8386 ON checklist (created_by_id)');
        $this->addSql('CREATE INDEX IDX_5C696D2F5DA0FB8 ON checklist (template_id)');
        $this->addSql('CREATE TABLE entry (id CHAR(36) NOT NULL --(DC2Type:uuid)
        , checklist_item_id CHAR(36) NOT NULL --(DC2Type:uuid)
        , user_id CHAR(36) NOT NULL --(DC2Type:uuid)
        , value TEXT CHECK(value IN (\'nope\', \'na\', \'done\')) NOT NULL --(DC2Type:ItemStatus)
        , date_time_created DATETIME NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_2B219D707E0892A4 ON entry (checklist_item_id)');
        $this->addSql('CREATE INDEX IDX_2B219D70A76ED395 ON entry (user_id)');
        $this->addSql('CREATE TABLE item (id CHAR(36) NOT NULL --(DC2Type:uuid)
        , section_id CHAR(36) NOT NULL --(DC2Type:uuid)
        , name VARCHAR(255) NOT NULL, sort_order INTEGER NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_1F1B251ED823E37A ON item (section_id)');
        $this->addSql('CREATE TABLE section (id CHAR(36) NOT NULL --(DC2Type:uuid)
        , checklist_id CHAR(36) NOT NULL --(DC2Type:uuid)
        , name VARCHAR(255) NOT NULL, sort_order INTEGER NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_2D737AEFB16D08A7 ON section (checklist_id)');
        $this->addSql('CREATE TABLE template (id CHAR(36) NOT NULL --(DC2Type:uuid)
        , name VARCHAR(255) NOT NULL, template_file VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE user (id CHAR(36) NOT NULL --(DC2Type:uuid)
        , email VARCHAR(255) NOT NULL, roles CLOB NOT NULL --(DC2Type:json)
        , password VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('DROP TABLE checklist');
        $this->addSql('DROP TABLE entry');
        $this->addSql('DROP TABLE item');
        $this->addSql('DROP TABLE section');
        $this->addSql('DROP TABLE template');
        $this->addSql('DROP TABLE user');
    }
}
