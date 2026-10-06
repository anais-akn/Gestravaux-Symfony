<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260122103346 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE bien (id INT AUTO_INCREMENT NOT NULL, adresse VARCHAR(255) NOT NULL, ville VARCHAR(100) NOT NULL, code_postal VARCHAR(20) NOT NULL, surface NUMERIC(10, 2) NOT NULL, utilisateur_id INT NOT NULL, INDEX IDX_45EDC386FB88E14F (utilisateur_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE categorie (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(100) NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE chantier (id INT AUTO_INCREMENT NOT NULL, date_creation DATETIME NOT NULL, date_validation DATETIME DEFAULT NULL, statut VARCHAR(50) NOT NULL, description LONGTEXT NOT NULL, bien_id INT NOT NULL, inspecteur_id INT NOT NULL, INDEX IDX_636F27F6BD95B80F (bien_id), INDEX IDX_636F27F6B7728AA0 (inspecteur_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE devis_entrepreneur (id INT AUTO_INCREMENT NOT NULL, date_debut DATETIME NOT NULL, duree_estimee_jour INT NOT NULL, statut VARCHAR(50) NOT NULL, entrepreneur_id INT DEFAULT NULL, chantier_id INT DEFAULT NULL, INDEX IDX_8525C1FE283063EA (entrepreneur_id), INDEX IDX_8525C1FED0C0049D (chantier_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE devis_entrepreneur_prestataire (id INT AUTO_INCREMENT NOT NULL, prix_unitaire NUMERIC(10, 2) NOT NULL, devis_entrepeneur_id INT DEFAULT NULL, prestation_id INT DEFAULT NULL, INDEX IDX_76329550A793AE3 (devis_entrepeneur_id), INDEX IDX_763295509E45C554 (prestation_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE devis_type (id INT AUTO_INCREMENT NOT NULL, intitule VARCHAR(200) NOT NULL, date_creation DATETIME NOT NULL, chantier_id INT DEFAULT NULL, INDEX IDX_C30C36F6D0C0049D (chantier_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE devis_type_prestataire (devis_type_id INT NOT NULL, prestataire_id INT NOT NULL, INDEX IDX_CE06C3D657A217CF (devis_type_id), INDEX IDX_CE06C3D6BE3DB2B7 (prestataire_id), PRIMARY KEY (devis_type_id, prestataire_id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE devis_type_entrepreneur (devis_type_id INT NOT NULL, entrepreneur_id INT NOT NULL, INDEX IDX_702AC0C557A217CF (devis_type_id), INDEX IDX_702AC0C5283063EA (entrepreneur_id), PRIMARY KEY (devis_type_id, entrepreneur_id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE devis_type_prestation (id INT AUTO_INCREMENT NOT NULL, quantite INT NOT NULL, devis_type_id INT NOT NULL, prestataire_id INT NOT NULL, INDEX IDX_2405934557A217CF (devis_type_id), INDEX IDX_24059345BE3DB2B7 (prestataire_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE document (id INT AUTO_INCREMENT NOT NULL, type_document VARCHAR(100) NOT NULL, chemin_fichier VARCHAR(500) NOT NULL, date_upload DATETIME NOT NULL, chantier_id INT DEFAULT NULL, INDEX IDX_D8698A76D0C0049D (chantier_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE entrepreneur (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(200) NOT NULL, siret VARCHAR(20) NOT NULL, email VARCHAR(255) NOT NULL, telephone VARCHAR(50) NOT NULL, adresse VARCHAR(250) NOT NULL, ville VARCHAR(100) NOT NULL, code_postal VARCHAR(20) NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE entrepreneur_categorie (entrepreneur_id INT NOT NULL, categorie_id INT NOT NULL, INDEX IDX_7B6E3FA0283063EA (entrepreneur_id), INDEX IDX_7B6E3FA0BCF5E72D (categorie_id), PRIMARY KEY (entrepreneur_id, categorie_id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE inspecteur (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(100) NOT NULL, prenom VARCHAR(100) NOT NULL, email VARCHAR(255) NOT NULL, telephone VARCHAR(255) NOT NULL, adresse VARCHAR(255) NOT NULL, ville VARCHAR(100) NOT NULL, code_postal VARCHAR(20) NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE photo (id INT AUTO_INCREMENT NOT NULL, chemin_fichier VARCHAR(500) NOT NULL, description VARCHAR(500) NOT NULL, date_prise DATETIME NOT NULL, chantier_id INT NOT NULL, INDEX IDX_14B78418D0C0049D (chantier_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE prestataire (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(200) NOT NULL, description LONGTEXT NOT NULL, prix_base NUMERIC(10, 2) NOT NULL, categorie_id INT DEFAULT NULL, INDEX IDX_60A26480BCF5E72D (categorie_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE prestataire_entrepreneur (prestataire_id INT NOT NULL, entrepreneur_id INT NOT NULL, INDEX IDX_5B1F8793BE3DB2B7 (prestataire_id), INDEX IDX_5B1F8793283063EA (entrepreneur_id), PRIMARY KEY (prestataire_id, entrepreneur_id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE utilisateur (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, nom VARCHAR(100) NOT NULL, prenom VARCHAR(100) NOT NULL, telephone VARCHAR(50) DEFAULT NULL, adresse VARCHAR(255) DEFAULT NULL, ville VARCHAR(100) DEFAULT NULL, code_postal VARCHAR(20) DEFAULT NULL, UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL (email), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE bien ADD CONSTRAINT FK_45EDC386FB88E14F FOREIGN KEY (utilisateur_id) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE chantier ADD CONSTRAINT FK_636F27F6BD95B80F FOREIGN KEY (bien_id) REFERENCES bien (id)');
        $this->addSql('ALTER TABLE chantier ADD CONSTRAINT FK_636F27F6B7728AA0 FOREIGN KEY (inspecteur_id) REFERENCES inspecteur (id)');
        $this->addSql('ALTER TABLE devis_entrepreneur ADD CONSTRAINT FK_8525C1FE283063EA FOREIGN KEY (entrepreneur_id) REFERENCES entrepreneur (id)');
        $this->addSql('ALTER TABLE devis_entrepreneur ADD CONSTRAINT FK_8525C1FED0C0049D FOREIGN KEY (chantier_id) REFERENCES chantier (id)');
        $this->addSql('ALTER TABLE devis_entrepreneur_prestataire ADD CONSTRAINT FK_76329550A793AE3 FOREIGN KEY (devis_entrepeneur_id) REFERENCES devis_entrepreneur (id)');
        $this->addSql('ALTER TABLE devis_entrepreneur_prestataire ADD CONSTRAINT FK_763295509E45C554 FOREIGN KEY (prestation_id) REFERENCES prestataire (id)');
        $this->addSql('ALTER TABLE devis_type ADD CONSTRAINT FK_C30C36F6D0C0049D FOREIGN KEY (chantier_id) REFERENCES chantier (id)');
        $this->addSql('ALTER TABLE devis_type_prestataire ADD CONSTRAINT FK_CE06C3D657A217CF FOREIGN KEY (devis_type_id) REFERENCES devis_type (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE devis_type_prestataire ADD CONSTRAINT FK_CE06C3D6BE3DB2B7 FOREIGN KEY (prestataire_id) REFERENCES prestataire (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE devis_type_entrepreneur ADD CONSTRAINT FK_702AC0C557A217CF FOREIGN KEY (devis_type_id) REFERENCES devis_type (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE devis_type_entrepreneur ADD CONSTRAINT FK_702AC0C5283063EA FOREIGN KEY (entrepreneur_id) REFERENCES entrepreneur (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE devis_type_prestation ADD CONSTRAINT FK_2405934557A217CF FOREIGN KEY (devis_type_id) REFERENCES devis_type (id)');
        $this->addSql('ALTER TABLE devis_type_prestation ADD CONSTRAINT FK_24059345BE3DB2B7 FOREIGN KEY (prestataire_id) REFERENCES prestataire (id)');
        $this->addSql('ALTER TABLE document ADD CONSTRAINT FK_D8698A76D0C0049D FOREIGN KEY (chantier_id) REFERENCES chantier (id)');
        $this->addSql('ALTER TABLE entrepreneur_categorie ADD CONSTRAINT FK_7B6E3FA0283063EA FOREIGN KEY (entrepreneur_id) REFERENCES entrepreneur (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE entrepreneur_categorie ADD CONSTRAINT FK_7B6E3FA0BCF5E72D FOREIGN KEY (categorie_id) REFERENCES categorie (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE photo ADD CONSTRAINT FK_14B78418D0C0049D FOREIGN KEY (chantier_id) REFERENCES chantier (id)');
        $this->addSql('ALTER TABLE prestataire ADD CONSTRAINT FK_60A26480BCF5E72D FOREIGN KEY (categorie_id) REFERENCES categorie (id)');
        $this->addSql('ALTER TABLE prestataire_entrepreneur ADD CONSTRAINT FK_5B1F8793BE3DB2B7 FOREIGN KEY (prestataire_id) REFERENCES prestataire (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE prestataire_entrepreneur ADD CONSTRAINT FK_5B1F8793283063EA FOREIGN KEY (entrepreneur_id) REFERENCES entrepreneur (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE bien DROP FOREIGN KEY FK_45EDC386FB88E14F');
        $this->addSql('ALTER TABLE chantier DROP FOREIGN KEY FK_636F27F6BD95B80F');
        $this->addSql('ALTER TABLE chantier DROP FOREIGN KEY FK_636F27F6B7728AA0');
        $this->addSql('ALTER TABLE devis_entrepreneur DROP FOREIGN KEY FK_8525C1FE283063EA');
        $this->addSql('ALTER TABLE devis_entrepreneur DROP FOREIGN KEY FK_8525C1FED0C0049D');
        $this->addSql('ALTER TABLE devis_entrepreneur_prestataire DROP FOREIGN KEY FK_76329550A793AE3');
        $this->addSql('ALTER TABLE devis_entrepreneur_prestataire DROP FOREIGN KEY FK_763295509E45C554');
        $this->addSql('ALTER TABLE devis_type DROP FOREIGN KEY FK_C30C36F6D0C0049D');
        $this->addSql('ALTER TABLE devis_type_prestataire DROP FOREIGN KEY FK_CE06C3D657A217CF');
        $this->addSql('ALTER TABLE devis_type_prestataire DROP FOREIGN KEY FK_CE06C3D6BE3DB2B7');
        $this->addSql('ALTER TABLE devis_type_entrepreneur DROP FOREIGN KEY FK_702AC0C557A217CF');
        $this->addSql('ALTER TABLE devis_type_entrepreneur DROP FOREIGN KEY FK_702AC0C5283063EA');
        $this->addSql('ALTER TABLE devis_type_prestation DROP FOREIGN KEY FK_2405934557A217CF');
        $this->addSql('ALTER TABLE devis_type_prestation DROP FOREIGN KEY FK_24059345BE3DB2B7');
        $this->addSql('ALTER TABLE document DROP FOREIGN KEY FK_D8698A76D0C0049D');
        $this->addSql('ALTER TABLE entrepreneur_categorie DROP FOREIGN KEY FK_7B6E3FA0283063EA');
        $this->addSql('ALTER TABLE entrepreneur_categorie DROP FOREIGN KEY FK_7B6E3FA0BCF5E72D');
        $this->addSql('ALTER TABLE photo DROP FOREIGN KEY FK_14B78418D0C0049D');
        $this->addSql('ALTER TABLE prestataire DROP FOREIGN KEY FK_60A26480BCF5E72D');
        $this->addSql('ALTER TABLE prestataire_entrepreneur DROP FOREIGN KEY FK_5B1F8793BE3DB2B7');
        $this->addSql('ALTER TABLE prestataire_entrepreneur DROP FOREIGN KEY FK_5B1F8793283063EA');
        $this->addSql('DROP TABLE bien');
        $this->addSql('DROP TABLE categorie');
        $this->addSql('DROP TABLE chantier');
        $this->addSql('DROP TABLE devis_entrepreneur');
        $this->addSql('DROP TABLE devis_entrepreneur_prestataire');
        $this->addSql('DROP TABLE devis_type');
        $this->addSql('DROP TABLE devis_type_prestataire');
        $this->addSql('DROP TABLE devis_type_entrepreneur');
        $this->addSql('DROP TABLE devis_type_prestation');
        $this->addSql('DROP TABLE document');
        $this->addSql('DROP TABLE entrepreneur');
        $this->addSql('DROP TABLE entrepreneur_categorie');
        $this->addSql('DROP TABLE inspecteur');
        $this->addSql('DROP TABLE photo');
        $this->addSql('DROP TABLE prestataire');
        $this->addSql('DROP TABLE prestataire_entrepreneur');
        $this->addSql('DROP TABLE utilisateur');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
