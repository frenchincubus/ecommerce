<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201116171728 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE manufacturer (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, country VARCHAR(255) DEFAULT NULL, address LONGTEXT DEFAULT NULL, logo VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tax (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, vat DOUBLE PRECISION NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE product ADD vat_id INT DEFAULT NULL, ADD manufacturer_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04ADB5B63A6B FOREIGN KEY (vat_id) REFERENCES tax (id)');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04ADA23B42D FOREIGN KEY (manufacturer_id) REFERENCES manufacturer (id)');
        $this->addSql('CREATE INDEX IDX_D34A04ADB5B63A6B ON product (vat_id)');
        $this->addSql('CREATE INDEX IDX_D34A04ADA23B42D ON product (manufacturer_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04ADA23B42D');
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04ADB5B63A6B');
        $this->addSql('DROP TABLE manufacturer');
        $this->addSql('DROP TABLE tax');
        $this->addSql('DROP INDEX IDX_D34A04ADB5B63A6B ON product');
        $this->addSql('DROP INDEX IDX_D34A04ADA23B42D ON product');
        $this->addSql('ALTER TABLE product DROP vat_id, DROP manufacturer_id');
    }
}
