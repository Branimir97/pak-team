<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201020070420 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE admin_panel ADD user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE admin_panel ADD CONSTRAINT FK_1B80E486A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_1B80E486A76ED395 ON admin_panel (user_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE admin_panel DROP FOREIGN KEY FK_1B80E486A76ED395');
        $this->addSql('DROP INDEX IDX_1B80E486A76ED395 ON admin_panel');
        $this->addSql('ALTER TABLE admin_panel DROP user_id');
    }
}