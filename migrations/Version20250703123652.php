<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250703123652 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Suppression de la table user pour la recréer avec le formateur';
    }

    public function up(Schema $schema): void
    {
        // Supprimer la table user si elle existe
        $this->addSql('DROP TABLE IF EXISTS user');
    }

    public function down(Schema $schema): void
    {
        // Recréer la table user basique (peut être modifié selon les besoins)
        $this->addSql('CREATE TABLE user (
            id INT AUTO_INCREMENT NOT NULL,
            email VARCHAR(180) NOT NULL,
            roles JSON NOT NULL,
            password VARCHAR(255) NOT NULL,
            UNIQUE INDEX UNIQ_8D93D649E7927C74 (email),
            PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }
}
