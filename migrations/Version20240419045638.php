<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240419045638 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE exam_result (id INT AUTO_INCREMENT NOT NULL, user_id_id INT NOT NULL, exam_id_id INT NOT NULL, result INT NOT NULL, INDEX IDX_D85997999D86650F (user_id_id), INDEX IDX_D85997992DA198E7 (exam_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE exam_result ADD CONSTRAINT FK_D85997999D86650F FOREIGN KEY (user_id_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE exam_result ADD CONSTRAINT FK_D85997992DA198E7 FOREIGN KEY (exam_id_id) REFERENCES exam (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE exam_result DROP FOREIGN KEY FK_D85997999D86650F');
        $this->addSql('ALTER TABLE exam_result DROP FOREIGN KEY FK_D85997992DA198E7');
        $this->addSql('DROP TABLE exam_result');
    }
}
