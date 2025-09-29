<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250929072644 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE user_courses (user_id INT NOT NULL, course_id INT NOT NULL, INDEX IDX_F1A84446A76ED395 (user_id), INDEX IDX_F1A84446591CC992 (course_id), PRIMARY KEY(user_id, course_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE user_courses ADD CONSTRAINT FK_F1A84446A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_courses ADD CONSTRAINT FK_F1A84446591CC992 FOREIGN KEY (course_id) REFERENCES course (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_courses DROP FOREIGN KEY FK_F1A84446A76ED395');
        $this->addSql('ALTER TABLE user_courses DROP FOREIGN KEY FK_F1A84446591CC992');
        $this->addSql('DROP TABLE user_courses');
    }
}
