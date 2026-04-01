-- McMaster Venture Capital Club – Database Schema
-- Run this file once to set up all tables and seed data.

CREATE DATABASE IF NOT EXISTS mvcc CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE mvcc;

-- ─────────────────────────────────────────────
--  TABLES
-- ─────────────────────────────────────────────

CREATE TABLE IF NOT EXISTS admins (
    id           INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    username     VARCHAR(80)  NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    created_at   TIMESTAMP    DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS members (
    id            INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    full_name     VARCHAR(150) NOT NULL,
    email         VARCHAR(255) NOT NULL UNIQUE,
    program       VARCHAR(150),
    year_of_study TINYINT UNSIGNED,
    interest_area VARCHAR(150),
    joined_at     TIMESTAMP    DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS events (
    id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title       VARCHAR(255) NOT NULL,
    event_date  DATE         NOT NULL,
    description TEXT,
    image_url   VARCHAR(500),
    created_at  TIMESTAMP    DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS partners (
    id           INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name         VARCHAR(200) NOT NULL,
    website_url  VARCHAR(500),
    logo_url     VARCHAR(500),
    category     ENUM('partner','associated') NOT NULL DEFAULT 'partner'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS executives (
    id            INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    full_name     VARCHAR(150) NOT NULL,
    role          VARCHAR(150),
    linkedin_url  VARCHAR(500),
    photo_url     VARCHAR(500),
    display_order TINYINT UNSIGNED DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ─────────────────────────────────────────────
--  SEED DATA — Executives
-- ─────────────────────────────────────────────

INSERT INTO executives (full_name, role, linkedin_url, photo_url, display_order) VALUES
('Veer Sarin',         'Co-founder',       NULL, NULL, 1),
('Diya Shah',          'Co-founder',       NULL, NULL, 2),
('Benicio Uhart',      'Co-founder',       NULL, NULL, 3),
('Josh Michell',       'VP Operations',    NULL, NULL, 4),
('Hannah Lewin',       'VP Marketing',     NULL, NULL, 5),
('Hunaid Rajkotwala',  'Co-VP Education',  NULL, NULL, 6),
('Abhay Shenoy',       'Co-VP Education',  NULL, NULL, 7);

-- ─────────────────────────────────────────────
--  SEED DATA — Partners
-- ─────────────────────────────────────────────

INSERT INTO partners (name, website_url, logo_url, category) VALUES
('Opennote (YC 2025)',            NULL, NULL, 'partner'),
('Golden Ventures',               'https://goldenventures.com', NULL, 'partner'),
('DeGroote Entrepreneurship',     NULL, NULL, 'partner'),
('Build Canada',                  NULL, NULL, 'partner'),
('Toronto Tech Week',             NULL, NULL, 'partner'),
('Gitcloud',                      NULL, NULL, 'partner'),
('The Forge',                     'https://theforge.mcmaster.ca', NULL, 'partner'),
('McMaster Student Seed Fund',    NULL, NULL, 'partner'),
('The Clinic',                    NULL, NULL, 'partner'),
('DeGroote School of Business',   'https://degroote.mcmaster.ca', NULL, 'partner'),
('Portage Ventures',              'https://portagevisions.com', NULL, 'partner'),
('LvlUp Ventures',                NULL, NULL, 'associated'),
('GoAhead Ventures',              NULL, NULL, 'associated'),
('Rivo',                          NULL, NULL, 'associated'),
('Amano',                         NULL, NULL, 'associated');

-- ─────────────────────────────────────────────
--  SEED DATA — Events
-- ─────────────────────────────────────────────

INSERT INTO events (title, event_date, description, image_url) VALUES
(
  'Build Canada x MVCC Event with Colin Mang',
  '2024-11-01',
  'McMaster Venture Capital Club partnered with Build Canada for an exclusive event featuring Colin Mang. Members gained insights into the Canadian startup ecosystem, venture trends, and opportunities for student entrepreneurs.',
  NULL  -- TODO: swap in real image
);

-- ─────────────────────────────────────────────
--  SEED DATA — Default Admin
--  Username: admin  |  Password: changeme123
--  Run password_hash('changeme123', PASSWORD_DEFAULT) in PHP to get a real hash.
-- ─────────────────────────────────────────────

INSERT INTO admins (username, password_hash) VALUES
('admin', '$2y$12$Nq5RxKtZRNLBGl9K3IH9WuQe7YzMrVw1lD3JYn4BkK5A2oFxRpGzO');
-- ^ placeholder hash — regenerate with: echo password_hash('changeme123', PASSWORD_DEFAULT);
