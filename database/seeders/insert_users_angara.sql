-- =============================================================================
-- Script de création des utilisateurs Angara
-- =============================================================================
-- Rôles : Gestionnaire A (role_id=13), Analyste financier B (role_id=14), Chef d'agence C (role_id=12), IT (role_id=1)
-- Mot de passe pour tous : bcpme (hash bcrypt)
-- Extension email : @angara.cm
--
-- Règles :
--   - Tout    : 3 comptes par personne (Gestionnaire + Analyste + Chef d'agence)
--   - A+B     : 2 comptes par personne (Gestionnaire + Analyste)
--   - B       : 1 compte Analyste
--   - C       : 1 compte Chef d'agence
--   - IT      : 1 compte, role_id=1, agence_id=0
--   - Autres  : agence_id=1
-- =============================================================================

SET @password_hash = '$2y$10$7umqc56gcE95xU4QX2VmS.UGnUfpOiwSaaYqES17aMAtMG51sJIUG';

-- -----------------------------------------------------------------------------
-- 1. HAMAN AMADOU (Tout) : 3 comptes
-- -----------------------------------------------------------------------------
INSERT INTO users (name, role_id, email, password, agence_id, created_at, updated_at) VALUES
('HAMAN AMADOU', 13, 'amadou.haman@angara.cm', @password_hash, 1, NOW(), NOW()),
('HAMAN AMADOU', 14, 'amadou.haman1@angara.cm', @password_hash, 1, NOW(), NOW()),
('HAMAN AMADOU', 12, 'amadou.haman2@angara.cm', @password_hash, 1, NOW(), NOW());

-- -----------------------------------------------------------------------------
-- 2. YAKOUBOU MOUSSA (A+B) : 2 comptes
-- -----------------------------------------------------------------------------
INSERT INTO users (name, role_id, email, password, agence_id, created_at, updated_at) VALUES
('YAKOUBOU MOUSSA', 13, 'moussa.yakoubou@angara.cm', @password_hash, 1, NOW(), NOW()),
('YAKOUBOU MOUSSA', 14, 'moussa.yakoubou1@angara.cm', @password_hash, 1, NOW(), NOW());

-- -----------------------------------------------------------------------------
-- 3. MBENA NDONGO (A+B) : 2 comptes
-- -----------------------------------------------------------------------------
INSERT INTO users (name, role_id, email, password, agence_id, created_at, updated_at) VALUES
('MBENA NDONGO', 13, 'ndongo.mbena@angara.cm', @password_hash, 1, NOW(), NOW()),
('MBENA NDONGO', 14, 'ndongo.mbena1@angara.cm', @password_hash, 1, NOW(), NOW());

-- -----------------------------------------------------------------------------
-- 4. TABI FABIEN (C) : 1 compte Chef d'agence
-- -----------------------------------------------------------------------------
INSERT INTO users (name, role_id, email, password, agence_id, created_at, updated_at) VALUES
('TABI FABIEN', 12, 'fabien.tabi@angara.cm', @password_hash, 1, NOW(), NOW());

-- -----------------------------------------------------------------------------
-- 5. HASSAN MEY (B) : 1 compte Analyste
-- -----------------------------------------------------------------------------
INSERT INTO users (name, role_id, email, password, agence_id, created_at, updated_at) VALUES
('HASSAN MEY', 14, 'mey.hassan@angara.cm', @password_hash, 1, NOW(), NOW());

-- -----------------------------------------------------------------------------
-- 6. MAIRAMOU BOBO (B) : 1 compte Analyste
-- -----------------------------------------------------------------------------
INSERT INTO users (name, role_id, email, password, agence_id, created_at, updated_at) VALUES
('MAIRAMOU BOBO', 14, 'bobo.mairamou@angara.cm', @password_hash, 1, NOW(), NOW());

-- -----------------------------------------------------------------------------
-- 7. GALLAH LESLIE (B) : 1 compte Analyste
-- -----------------------------------------------------------------------------
INSERT INTO users (name, role_id, email, password, agence_id, created_at, updated_at) VALUES
('GALLAH LESLIE', 14, 'leslie.gallah@angara.cm', @password_hash, 1, NOW(), NOW());

-- -----------------------------------------------------------------------------
-- 8. KAMLA VICTOR (A+B) : 2 comptes
-- -----------------------------------------------------------------------------
INSERT INTO users (name, role_id, email, password, agence_id, created_at, updated_at) VALUES
('KAMLA VICTOR', 13, 'victor.kamla@angara.cm', @password_hash, 1, NOW(), NOW()),
('KAMLA VICTOR', 14, 'victor.kamla1@angara.cm', @password_hash, 1, NOW(), NOW());

-- -----------------------------------------------------------------------------
-- 9. MINTOM YVETTE (A+B) : 2 comptes
-- -----------------------------------------------------------------------------
INSERT INTO users (name, role_id, email, password, agence_id, created_at, updated_at) VALUES
('MINTOM YVETTE', 13, 'yvette.mintom@angara.cm', @password_hash, 1, NOW(), NOW()),
('MINTOM YVETTE', 14, 'yvette.mintom1@angara.cm', @password_hash, 1, NOW(), NOW());

-- -----------------------------------------------------------------------------
-- 10-13. IT (role_id=1, agence_id=0)
-- -----------------------------------------------------------------------------
INSERT INTO users (name, role_id, email, password, agence_id, created_at, updated_at) VALUES
('NDJOMO EKO', 1, 'eko.ndjomo@angara.cm', @password_hash, 0, NOW(), NOW()),
('MANGA Welisane', 1, 'welisane.manga@angara.cm', @password_hash, 0, NOW(), NOW()),
('YAKANA BABOYA', 1, 'baboya.yakana@angara.cm', @password_hash, 0, NOW(), NOW()),
('WAMBA FOKOU', 1, 'fokou.wamba@angara.cm', @password_hash, 0, NOW(), NOW());

-- -----------------------------------------------------------------------------
-- 14. BLAISE ROLAND ZE (A+B) : 2 comptes
-- -----------------------------------------------------------------------------
INSERT INTO users (name, role_id, email, password, agence_id, created_at, updated_at) VALUES
('BLAISE ROLAND ZE', 13, 'roland.blaise@angara.cm', @password_hash, 1, NOW(), NOW()),
('BLAISE ROLAND ZE', 14, 'roland.blaise1@angara.cm', @password_hash, 1, NOW(), NOW());

-- -----------------------------------------------------------------------------
-- 15. ESSAKA SANDRINE (A+B) : 2 comptes
-- -----------------------------------------------------------------------------
INSERT INTO users (name, role_id, email, password, agence_id, created_at, updated_at) VALUES
('ESSAKA SANDRINE', 13, 'sandrine.essaka@angara.cm', @password_hash, 1, NOW(), NOW()),
('ESSAKA SANDRINE', 14, 'sandrine.essaka1@angara.cm', @password_hash, 1, NOW(), NOW());

-- -----------------------------------------------------------------------------
-- 16. EKITIKE BONDIMA (A+B) : 2 comptes
-- -----------------------------------------------------------------------------
INSERT INTO users (name, role_id, email, password, agence_id, created_at, updated_at) VALUES
('EKITIKE BONDIMA', 13, 'bondima.ekitike@angara.cm', @password_hash, 1, NOW(), NOW()),
('EKITIKE BONDIMA', 14, 'bondima.ekitike1@angara.cm', @password_hash, 1, NOW(), NOW());
