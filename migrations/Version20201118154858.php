<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201118154858 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE payment (id INT AUTO_INCREMENT NOT NULL, customer_id INT NOT NULL, order_id_id INT NOT NULL, type_payment_id INT NOT NULL, name VARCHAR(255) NOT NULL, date DATETIME NOT NULL, amount DOUBLE PRECISION NOT NULL, INDEX IDX_6D28840D9395C3F3 (customer_id), UNIQUE INDEX UNIQ_6D28840DFCDAEAAA (order_id_id), INDEX IDX_6D28840D19C0759E (type_payment_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE type_payment (id INT AUTO_INCREMENT NOT NULL, payment_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, informations LONGTEXT NOT NULL, INDEX IDX_4509EBF84C3A3BB (payment_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE payment ADD CONSTRAINT FK_6D28840D9395C3F3 FOREIGN KEY (customer_id) REFERENCES customer (id)');
        $this->addSql('ALTER TABLE payment ADD CONSTRAINT FK_6D28840DFCDAEAAA FOREIGN KEY (order_id_id) REFERENCES `order` (id)');
        $this->addSql('ALTER TABLE payment ADD CONSTRAINT FK_6D28840D19C0759E FOREIGN KEY (type_payment_id) REFERENCES type_payment (id)');
        $this->addSql('ALTER TABLE type_payment ADD CONSTRAINT FK_4509EBF84C3A3BB FOREIGN KEY (payment_id) REFERENCES payment (id)');
        $this->addSql('ALTER TABLE `order` ADD state INT NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE type_payment DROP FOREIGN KEY FK_4509EBF84C3A3BB');
        $this->addSql('ALTER TABLE payment DROP FOREIGN KEY FK_6D28840D19C0759E');
        $this->addSql('DROP TABLE payment');
        $this->addSql('DROP TABLE type_payment');
        $this->addSql('ALTER TABLE `order` DROP state');
    }
}
