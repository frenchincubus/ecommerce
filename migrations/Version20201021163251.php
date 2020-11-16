<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201021163251 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE address (id INT AUTO_INCREMENT NOT NULL, customer_id_id INT NOT NULL, address VARCHAR(255) NOT NULL, street_number VARCHAR(10) DEFAULT NULL, zipcode VARCHAR(10) NOT NULL, city VARCHAR(255) NOT NULL, country VARCHAR(255) NOT NULL, INDEX IDX_D4E6F81B171EB6C (customer_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE cart (id INT AUTO_INCREMENT NOT NULL, customer_id_id INT NOT NULL, total_price NUMERIC(10, 2) NOT NULL, INDEX IDX_BA388B7B171EB6C (customer_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE cart_products (id INT AUTO_INCREMENT NOT NULL, cart_id_id INT NOT NULL, quantity INT NOT NULL, date DATETIME NOT NULL, INDEX IDX_2D25153120AEF35F (cart_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE customer (id INT AUTO_INCREMENT NOT NULL, firstname VARCHAR(255) NOT NULL, lastname VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, birthdate DATE NOT NULL, password VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `order` (id INT AUTO_INCREMENT NOT NULL, cart_id_id INT NOT NULL, date DATETIME NOT NULL, UNIQUE INDEX UNIQ_F529939820AEF35F (cart_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product (id INT AUTO_INCREMENT NOT NULL, cart_products_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, reference VARCHAR(255) NOT NULL, ean13 VARCHAR(255) NOT NULL, price NUMERIC(7, 2) DEFAULT NULL, weight NUMERIC(5, 3) DEFAULT NULL, description LONGTEXT DEFAULT NULL, ttc_price NUMERIC(7, 2) DEFAULT NULL, INDEX IDX_D34A04AD3F5978E9 (cart_products_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE quantity (id INT AUTO_INCREMENT NOT NULL, product_id_id INT NOT NULL, quantity INT NOT NULL, UNIQUE INDEX UNIQ_9FF31636DE18E50B (product_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE address ADD CONSTRAINT FK_D4E6F81B171EB6C FOREIGN KEY (customer_id_id) REFERENCES customer (id)');
        $this->addSql('ALTER TABLE cart ADD CONSTRAINT FK_BA388B7B171EB6C FOREIGN KEY (customer_id_id) REFERENCES customer (id)');
        $this->addSql('ALTER TABLE cart_products ADD CONSTRAINT FK_2D25153120AEF35F FOREIGN KEY (cart_id_id) REFERENCES cart (id)');
        $this->addSql('ALTER TABLE `order` ADD CONSTRAINT FK_F529939820AEF35F FOREIGN KEY (cart_id_id) REFERENCES cart (id)');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04AD3F5978E9 FOREIGN KEY (cart_products_id) REFERENCES cart_products (id)');
        $this->addSql('ALTER TABLE quantity ADD CONSTRAINT FK_9FF31636DE18E50B FOREIGN KEY (product_id_id) REFERENCES product (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE cart_products DROP FOREIGN KEY FK_2D25153120AEF35F');
        $this->addSql('ALTER TABLE `order` DROP FOREIGN KEY FK_F529939820AEF35F');
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04AD3F5978E9');
        $this->addSql('ALTER TABLE address DROP FOREIGN KEY FK_D4E6F81B171EB6C');
        $this->addSql('ALTER TABLE cart DROP FOREIGN KEY FK_BA388B7B171EB6C');
        $this->addSql('ALTER TABLE quantity DROP FOREIGN KEY FK_9FF31636DE18E50B');
        $this->addSql('DROP TABLE address');
        $this->addSql('DROP TABLE cart');
        $this->addSql('DROP TABLE cart_products');
        $this->addSql('DROP TABLE customer');
        $this->addSql('DROP TABLE `order`');
        $this->addSql('DROP TABLE product');
        $this->addSql('DROP TABLE quantity');
    }
}
