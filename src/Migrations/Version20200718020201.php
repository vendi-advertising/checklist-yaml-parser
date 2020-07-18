<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200718020201 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('DROP INDEX IDX_5C696D2F5DA0FB8');
        $this->addSql('DROP INDEX IDX_5C696D2FB03A8386');
        $this->addSql('CREATE TEMPORARY TABLE __temp__checklist AS SELECT id, created_by_id, template_id, description FROM checklist');
        $this->addSql('DROP TABLE checklist');
        $this->addSql('CREATE TABLE checklist (id CHAR(36) NOT NULL COLLATE BINARY --(DC2Type:uuid)
        , created_by_id INTEGER NOT NULL, template_id CHAR(36) NOT NULL COLLATE BINARY --(DC2Type:uuid)
        , description VARCHAR(1024) NOT NULL COLLATE BINARY, PRIMARY KEY(id), CONSTRAINT FK_5C696D2FB03A8386 FOREIGN KEY (created_by_id) REFERENCES user (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_5C696D2F5DA0FB8 FOREIGN KEY (template_id) REFERENCES template (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO checklist (id, created_by_id, template_id, description) SELECT id, created_by_id, template_id, description FROM __temp__checklist');
        $this->addSql('DROP TABLE __temp__checklist');
        $this->addSql('CREATE INDEX IDX_5C696D2F5DA0FB8 ON checklist (template_id)');
        $this->addSql('CREATE INDEX IDX_5C696D2FB03A8386 ON checklist (created_by_id)');
        $this->addSql('DROP INDEX IDX_2B219D70A76ED395');
        $this->addSql('DROP INDEX IDX_2B219D707E0892A4');
        $this->addSql('CREATE TEMPORARY TABLE __temp__entry AS SELECT id, checklist_item_id, user_id, value, date_time_created FROM entry');
        $this->addSql('DROP TABLE entry');
        $this->addSql('CREATE TABLE entry (id CHAR(36) NOT NULL COLLATE BINARY --(DC2Type:uuid)
        , checklist_item_id CHAR(36) NOT NULL COLLATE BINARY --(DC2Type:uuid)
        , user_id INTEGER NOT NULL, value TEXT CHECK(value IN (\'nope\', \'n/a\', \'done\')) NOT NULL COLLATE BINARY --(DC2Type:ItemStatus)
        , date_time_created DATETIME NOT NULL, PRIMARY KEY(id), CONSTRAINT FK_2B219D707E0892A4 FOREIGN KEY (checklist_item_id) REFERENCES item (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_2B219D70A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO entry (id, checklist_item_id, user_id, value, date_time_created) SELECT id, checklist_item_id, user_id, value, date_time_created FROM __temp__entry');
        $this->addSql('DROP TABLE __temp__entry');
        $this->addSql('CREATE INDEX IDX_2B219D70A76ED395 ON entry (user_id)');
        $this->addSql('CREATE INDEX IDX_2B219D707E0892A4 ON entry (checklist_item_id)');
        $this->addSql('DROP INDEX IDX_1F1B251ED823E37A');
        $this->addSql('CREATE TEMPORARY TABLE __temp__item AS SELECT id, section_id, name, sort_order FROM item');
        $this->addSql('DROP TABLE item');
        $this->addSql('CREATE TABLE item (id CHAR(36) NOT NULL COLLATE BINARY --(DC2Type:uuid)
        , section_id CHAR(36) NOT NULL COLLATE BINARY --(DC2Type:uuid)
        , name VARCHAR(255) NOT NULL COLLATE BINARY, sort_order INTEGER NOT NULL, PRIMARY KEY(id), CONSTRAINT FK_1F1B251ED823E37A FOREIGN KEY (section_id) REFERENCES section (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO item (id, section_id, name, sort_order) SELECT id, section_id, name, sort_order FROM __temp__item');
        $this->addSql('DROP TABLE __temp__item');
        $this->addSql('CREATE INDEX IDX_1F1B251ED823E37A ON item (section_id)');
        $this->addSql('DROP INDEX IDX_2D737AEFB16D08A7');
        $this->addSql('CREATE TEMPORARY TABLE __temp__section AS SELECT id, checklist_id, name, sort_order FROM section');
        $this->addSql('DROP TABLE section');
        $this->addSql('CREATE TABLE section (id CHAR(36) NOT NULL COLLATE BINARY --(DC2Type:uuid)
        , checklist_id CHAR(36) NOT NULL COLLATE BINARY --(DC2Type:uuid)
        , name VARCHAR(255) NOT NULL COLLATE BINARY, sort_order INTEGER NOT NULL, PRIMARY KEY(id), CONSTRAINT FK_2D737AEFB16D08A7 FOREIGN KEY (checklist_id) REFERENCES checklist (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO section (id, checklist_id, name, sort_order) SELECT id, checklist_id, name, sort_order FROM __temp__section');
        $this->addSql('DROP TABLE __temp__section');
        $this->addSql('CREATE INDEX IDX_2D737AEFB16D08A7 ON section (checklist_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('DROP INDEX IDX_5C696D2FB03A8386');
        $this->addSql('DROP INDEX IDX_5C696D2F5DA0FB8');
        $this->addSql('CREATE TEMPORARY TABLE __temp__checklist AS SELECT id, created_by_id, template_id, description FROM checklist');
        $this->addSql('DROP TABLE checklist');
        $this->addSql('CREATE TABLE checklist (id CHAR(36) NOT NULL --(DC2Type:uuid)
        , created_by_id INTEGER NOT NULL, template_id CHAR(36) NOT NULL --(DC2Type:uuid)
        , description VARCHAR(1024) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('INSERT INTO checklist (id, created_by_id, template_id, description) SELECT id, created_by_id, template_id, description FROM __temp__checklist');
        $this->addSql('DROP TABLE __temp__checklist');
        $this->addSql('CREATE INDEX IDX_5C696D2FB03A8386 ON checklist (created_by_id)');
        $this->addSql('CREATE INDEX IDX_5C696D2F5DA0FB8 ON checklist (template_id)');
        $this->addSql('DROP INDEX IDX_2B219D707E0892A4');
        $this->addSql('DROP INDEX IDX_2B219D70A76ED395');
        $this->addSql('CREATE TEMPORARY TABLE __temp__entry AS SELECT id, checklist_item_id, user_id, value, date_time_created FROM entry');
        $this->addSql('DROP TABLE entry');
        $this->addSql('CREATE TABLE entry (id CHAR(36) NOT NULL --(DC2Type:uuid)
        , checklist_item_id CHAR(36) NOT NULL --(DC2Type:uuid)
        , user_id INTEGER NOT NULL, value TEXT CHECK(value IN (\'nope\', \'n/a\', \'done\')) NOT NULL --(DC2Type:ItemStatus)
        , date_time_created DATETIME NOT NULL, PRIMARY KEY(id))');
        $this->addSql('INSERT INTO entry (id, checklist_item_id, user_id, value, date_time_created) SELECT id, checklist_item_id, user_id, value, date_time_created FROM __temp__entry');
        $this->addSql('DROP TABLE __temp__entry');
        $this->addSql('CREATE INDEX IDX_2B219D707E0892A4 ON entry (checklist_item_id)');
        $this->addSql('CREATE INDEX IDX_2B219D70A76ED395 ON entry (user_id)');
        $this->addSql('DROP INDEX IDX_1F1B251ED823E37A');
        $this->addSql('CREATE TEMPORARY TABLE __temp__item AS SELECT id, section_id, name, sort_order FROM item');
        $this->addSql('DROP TABLE item');
        $this->addSql('CREATE TABLE item (id CHAR(36) NOT NULL --(DC2Type:uuid)
        , section_id CHAR(36) NOT NULL --(DC2Type:uuid)
        , name VARCHAR(255) NOT NULL, sort_order INTEGER NOT NULL, PRIMARY KEY(id))');
        $this->addSql('INSERT INTO item (id, section_id, name, sort_order) SELECT id, section_id, name, sort_order FROM __temp__item');
        $this->addSql('DROP TABLE __temp__item');
        $this->addSql('CREATE INDEX IDX_1F1B251ED823E37A ON item (section_id)');
        $this->addSql('DROP INDEX IDX_2D737AEFB16D08A7');
        $this->addSql('CREATE TEMPORARY TABLE __temp__section AS SELECT id, checklist_id, name, sort_order FROM section');
        $this->addSql('DROP TABLE section');
        $this->addSql('CREATE TABLE section (id CHAR(36) NOT NULL --(DC2Type:uuid)
        , checklist_id CHAR(36) NOT NULL --(DC2Type:uuid)
        , name VARCHAR(255) NOT NULL, sort_order INTEGER NOT NULL, PRIMARY KEY(id))');
        $this->addSql('INSERT INTO section (id, checklist_id, name, sort_order) SELECT id, checklist_id, name, sort_order FROM __temp__section');
        $this->addSql('DROP TABLE __temp__section');
        $this->addSql('CREATE INDEX IDX_2D737AEFB16D08A7 ON section (checklist_id)');
    }
}
