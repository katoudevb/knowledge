<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250911065802 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE certification ADD created_by_id INT DEFAULT NULL, ADD updated_by_id INT DEFAULT NULL, ADD created_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', ADD updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE certification ADD CONSTRAINT FK_6C3C6D75B03A8386 FOREIGN KEY (created_by_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE certification ADD CONSTRAINT FK_6C3C6D75896DBBDE FOREIGN KEY (updated_by_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_6C3C6D75B03A8386 ON certification (created_by_id)');
        $this->addSql('CREATE INDEX IDX_6C3C6D75896DBBDE ON certification (updated_by_id)');
        $this->addSql('ALTER TABLE course ADD created_by_id INT DEFAULT NULL, ADD updated_by_id INT DEFAULT NULL, ADD created_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', ADD updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE course ADD CONSTRAINT FK_169E6FB9B03A8386 FOREIGN KEY (created_by_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE course ADD CONSTRAINT FK_169E6FB9896DBBDE FOREIGN KEY (updated_by_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_169E6FB9B03A8386 ON course (created_by_id)');
        $this->addSql('CREATE INDEX IDX_169E6FB9896DBBDE ON course (updated_by_id)');
        $this->addSql('ALTER TABLE lesson ADD created_by_id INT DEFAULT NULL, ADD updated_by_id INT DEFAULT NULL, ADD created_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', ADD updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE lesson ADD CONSTRAINT FK_F87474F3B03A8386 FOREIGN KEY (created_by_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE lesson ADD CONSTRAINT FK_F87474F3896DBBDE FOREIGN KEY (updated_by_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_F87474F3B03A8386 ON lesson (created_by_id)');
        $this->addSql('CREATE INDEX IDX_F87474F3896DBBDE ON lesson (updated_by_id)');
        $this->addSql('ALTER TABLE purchase ADD created_by_id INT DEFAULT NULL, ADD updated_by_id INT DEFAULT NULL, ADD updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', CHANGE created_at created_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE purchase ADD CONSTRAINT FK_6117D13BB03A8386 FOREIGN KEY (created_by_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE purchase ADD CONSTRAINT FK_6117D13B896DBBDE FOREIGN KEY (updated_by_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_6117D13BB03A8386 ON purchase (created_by_id)');
        $this->addSql('CREATE INDEX IDX_6117D13B896DBBDE ON purchase (updated_by_id)');
        $this->addSql('ALTER TABLE theme ADD created_by_id INT DEFAULT NULL, ADD updated_by_id INT DEFAULT NULL, ADD created_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', ADD updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE theme ADD CONSTRAINT FK_9775E708B03A8386 FOREIGN KEY (created_by_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE theme ADD CONSTRAINT FK_9775E708896DBBDE FOREIGN KEY (updated_by_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_9775E708B03A8386 ON theme (created_by_id)');
        $this->addSql('CREATE INDEX IDX_9775E708896DBBDE ON theme (updated_by_id)');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649896DBBDE');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649B03A8386');
        $this->addSql('DROP INDEX IDX_8D93D649896DBBDE ON user');
        $this->addSql('DROP INDEX IDX_8D93D649B03A8386 ON user');
        $this->addSql('ALTER TABLE user ADD cree_par_id INT DEFAULT NULL, ADD modifie_par_id INT DEFAULT NULL, ADD cree_le DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', ADD modifie_le DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', DROP created_by_id, DROP updated_by_id, DROP created_at, DROP updated_at');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649FC29C013 FOREIGN KEY (cree_par_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649553B2554 FOREIGN KEY (modifie_par_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_8D93D649FC29C013 ON user (cree_par_id)');
        $this->addSql('CREATE INDEX IDX_8D93D649553B2554 ON user (modifie_par_id)');
        $this->addSql('ALTER TABLE user_lesson ADD created_by_id INT DEFAULT NULL, ADD updated_by_id INT DEFAULT NULL, ADD created_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', ADD updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE user_lesson ADD CONSTRAINT FK_9D266FCEB03A8386 FOREIGN KEY (created_by_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user_lesson ADD CONSTRAINT FK_9D266FCE896DBBDE FOREIGN KEY (updated_by_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_9D266FCEB03A8386 ON user_lesson (created_by_id)');
        $this->addSql('CREATE INDEX IDX_9D266FCE896DBBDE ON user_lesson (updated_by_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE certification DROP FOREIGN KEY FK_6C3C6D75B03A8386');
        $this->addSql('ALTER TABLE certification DROP FOREIGN KEY FK_6C3C6D75896DBBDE');
        $this->addSql('DROP INDEX IDX_6C3C6D75B03A8386 ON certification');
        $this->addSql('DROP INDEX IDX_6C3C6D75896DBBDE ON certification');
        $this->addSql('ALTER TABLE certification DROP created_by_id, DROP updated_by_id, DROP created_at, DROP updated_at');
        $this->addSql('ALTER TABLE course DROP FOREIGN KEY FK_169E6FB9B03A8386');
        $this->addSql('ALTER TABLE course DROP FOREIGN KEY FK_169E6FB9896DBBDE');
        $this->addSql('DROP INDEX IDX_169E6FB9B03A8386 ON course');
        $this->addSql('DROP INDEX IDX_169E6FB9896DBBDE ON course');
        $this->addSql('ALTER TABLE course DROP created_by_id, DROP updated_by_id, DROP created_at, DROP updated_at');
        $this->addSql('ALTER TABLE lesson DROP FOREIGN KEY FK_F87474F3B03A8386');
        $this->addSql('ALTER TABLE lesson DROP FOREIGN KEY FK_F87474F3896DBBDE');
        $this->addSql('DROP INDEX IDX_F87474F3B03A8386 ON lesson');
        $this->addSql('DROP INDEX IDX_F87474F3896DBBDE ON lesson');
        $this->addSql('ALTER TABLE lesson DROP created_by_id, DROP updated_by_id, DROP created_at, DROP updated_at');
        $this->addSql('ALTER TABLE purchase DROP FOREIGN KEY FK_6117D13BB03A8386');
        $this->addSql('ALTER TABLE purchase DROP FOREIGN KEY FK_6117D13B896DBBDE');
        $this->addSql('DROP INDEX IDX_6117D13BB03A8386 ON purchase');
        $this->addSql('DROP INDEX IDX_6117D13B896DBBDE ON purchase');
        $this->addSql('ALTER TABLE purchase DROP created_by_id, DROP updated_by_id, DROP updated_at, CHANGE created_at created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE theme DROP FOREIGN KEY FK_9775E708B03A8386');
        $this->addSql('ALTER TABLE theme DROP FOREIGN KEY FK_9775E708896DBBDE');
        $this->addSql('DROP INDEX IDX_9775E708B03A8386 ON theme');
        $this->addSql('DROP INDEX IDX_9775E708896DBBDE ON theme');
        $this->addSql('ALTER TABLE theme DROP created_by_id, DROP updated_by_id, DROP created_at, DROP updated_at');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649FC29C013');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649553B2554');
        $this->addSql('DROP INDEX IDX_8D93D649FC29C013 ON user');
        $this->addSql('DROP INDEX IDX_8D93D649553B2554 ON user');
        $this->addSql('ALTER TABLE user ADD created_by_id INT DEFAULT NULL, ADD updated_by_id INT DEFAULT NULL, ADD created_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', ADD updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', DROP cree_par_id, DROP modifie_par_id, DROP cree_le, DROP modifie_le');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649896DBBDE FOREIGN KEY (updated_by_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649B03A8386 FOREIGN KEY (created_by_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_8D93D649896DBBDE ON user (updated_by_id)');
        $this->addSql('CREATE INDEX IDX_8D93D649B03A8386 ON user (created_by_id)');
        $this->addSql('ALTER TABLE user_lesson DROP FOREIGN KEY FK_9D266FCEB03A8386');
        $this->addSql('ALTER TABLE user_lesson DROP FOREIGN KEY FK_9D266FCE896DBBDE');
        $this->addSql('DROP INDEX IDX_9D266FCEB03A8386 ON user_lesson');
        $this->addSql('DROP INDEX IDX_9D266FCE896DBBDE ON user_lesson');
        $this->addSql('ALTER TABLE user_lesson DROP created_by_id, DROP updated_by_id, DROP created_at, DROP updated_at');
    }
}
