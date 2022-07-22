<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220722153024 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE commande_detail ADD product_id INT NOT NULL');
        $this->addSql('ALTER TABLE commande_detail ADD CONSTRAINT FK_2C5284464584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('CREATE INDEX IDX_2C5284464584665A ON commande_detail (product_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE commande_detail DROP FOREIGN KEY FK_2C5284464584665A');
        $this->addSql('DROP INDEX IDX_2C5284464584665A ON commande_detail');
        $this->addSql('ALTER TABLE commande_detail DROP product_id');
    }
}
