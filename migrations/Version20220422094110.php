<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220422094110 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE categorie (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(100) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `order` (id INT AUTO_INCREMENT NOT NULL, state_id INT NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', total_price DOUBLE PRECISION NOT NULL, INDEX IDX_F52993985D83CC1 (state_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE order_state (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(50) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product (id INT AUTO_INCREMENT NOT NULL, product_sel_id INT NOT NULL, libelle VARCHAR(100) NOT NULL, price DOUBLE PRECISION NOT NULL, stock INT NOT NULL, description LONGTEXT DEFAULT NULL, INDEX IDX_D34A04ADE72A33CC (product_sel_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product_categorie (product_id INT NOT NULL, categorie_id INT NOT NULL, INDEX IDX_27DD60B94584665A (product_id), INDEX IDX_27DD60B9BCF5E72D (categorie_id), PRIMARY KEY(product_id, categorie_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE seller (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(100) NOT NULL, city VARCHAR(255) NOT NULL, house_number INT NOT NULL, post_code INT NOT NULL, street VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE `order` ADD CONSTRAINT FK_F52993985D83CC1 FOREIGN KEY (state_id) REFERENCES order_state (id)');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04ADE72A33CC FOREIGN KEY (product_sel_id) REFERENCES seller (id)');
        $this->addSql('ALTER TABLE product_categorie ADD CONSTRAINT FK_27DD60B94584665A FOREIGN KEY (product_id) REFERENCES product (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE product_categorie ADD CONSTRAINT FK_27DD60B9BCF5E72D FOREIGN KEY (categorie_id) REFERENCES categorie (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE product_categorie DROP FOREIGN KEY FK_27DD60B9BCF5E72D');
        $this->addSql('ALTER TABLE `order` DROP FOREIGN KEY FK_F52993985D83CC1');
        $this->addSql('ALTER TABLE product_categorie DROP FOREIGN KEY FK_27DD60B94584665A');
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04ADE72A33CC');
        $this->addSql('DROP TABLE categorie');
        $this->addSql('DROP TABLE `order`');
        $this->addSql('DROP TABLE order_state');
        $this->addSql('DROP TABLE product');
        $this->addSql('DROP TABLE product_categorie');
        $this->addSql('DROP TABLE seller');
    }
}
