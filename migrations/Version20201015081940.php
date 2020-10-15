<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201015081940 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE account (uid VARCHAR(32) NOT NULL, ballance BIGINT NOT NULL, PRIMARY KEY(uid)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `transactions` (tid VARCHAR(255) NOT NULL, uid VARCHAR(255) NOT NULL, amount BIGINT NOT NULL, INDEX IDX_EAA81A4C539B0606 (uid), PRIMARY KEY(tid)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE `transactions` ADD CONSTRAINT FK_EAA81A4C539B0606 FOREIGN KEY (uid) REFERENCES account (uid)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `transactions` DROP FOREIGN KEY FK_EAA81A4C539B0606');
        $this->addSql('DROP TABLE account');
        $this->addSql('DROP TABLE `transactions`');
    }
}
