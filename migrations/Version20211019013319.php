<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211019013319 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE card (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, multiverse_id INT NOT NULL, mana_cost VARCHAR(255) NOT NULL, text LONGTEXT NOT NULL, UNIQUE INDEX UNIQ_161498D3713FCA37 (multiverse_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE card_color (card_id INT NOT NULL, color_id INT NOT NULL, INDEX IDX_5E62B92F4ACC9A20 (card_id), INDEX IDX_5E62B92F7ADA1FB5 (color_id), PRIMARY KEY(card_id, color_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE card_type (card_id INT NOT NULL, type_id INT NOT NULL, INDEX IDX_60ED558B4ACC9A20 (card_id), INDEX IDX_60ED558BC54C8C93 (type_id), PRIMARY KEY(card_id, type_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE color (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(190) NOT NULL, UNIQUE INDEX UNIQ_665648E95E237E06 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE deck (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, max_size INT NOT NULL, INDEX IDX_4FAC3637A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE deck_card (deck_id INT NOT NULL, card_id INT NOT NULL, INDEX IDX_2AF3DCED111948DC (deck_id), INDEX IDX_2AF3DCED4ACC9A20 (card_id), PRIMARY KEY(deck_id, card_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE type (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(190) NOT NULL, UNIQUE INDEX UNIQ_8CDE57295E237E06 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE card_color ADD CONSTRAINT FK_5E62B92F4ACC9A20 FOREIGN KEY (card_id) REFERENCES card (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE card_color ADD CONSTRAINT FK_5E62B92F7ADA1FB5 FOREIGN KEY (color_id) REFERENCES color (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE card_type ADD CONSTRAINT FK_60ED558B4ACC9A20 FOREIGN KEY (card_id) REFERENCES card (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE card_type ADD CONSTRAINT FK_60ED558BC54C8C93 FOREIGN KEY (type_id) REFERENCES type (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE deck ADD CONSTRAINT FK_4FAC3637A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE deck_card ADD CONSTRAINT FK_2AF3DCED111948DC FOREIGN KEY (deck_id) REFERENCES deck (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE deck_card ADD CONSTRAINT FK_2AF3DCED4ACC9A20 FOREIGN KEY (card_id) REFERENCES card (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE card_color DROP FOREIGN KEY FK_5E62B92F4ACC9A20');
        $this->addSql('ALTER TABLE card_type DROP FOREIGN KEY FK_60ED558B4ACC9A20');
        $this->addSql('ALTER TABLE deck_card DROP FOREIGN KEY FK_2AF3DCED4ACC9A20');
        $this->addSql('ALTER TABLE card_color DROP FOREIGN KEY FK_5E62B92F7ADA1FB5');
        $this->addSql('ALTER TABLE deck_card DROP FOREIGN KEY FK_2AF3DCED111948DC');
        $this->addSql('ALTER TABLE card_type DROP FOREIGN KEY FK_60ED558BC54C8C93');
        $this->addSql('ALTER TABLE deck DROP FOREIGN KEY FK_4FAC3637A76ED395');
        $this->addSql('DROP TABLE card');
        $this->addSql('DROP TABLE card_color');
        $this->addSql('DROP TABLE card_type');
        $this->addSql('DROP TABLE color');
        $this->addSql('DROP TABLE deck');
        $this->addSql('DROP TABLE deck_card');
        $this->addSql('DROP TABLE type');
        $this->addSql('DROP TABLE user');
    }
}
