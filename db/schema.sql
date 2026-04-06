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
    location    VARCHAR(255),
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
    bio           TEXT,
    linkedin_url  VARCHAR(500),
    photo_url     VARCHAR(500),
    display_order TINYINT UNSIGNED DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ─────────────────────────────────────────────
--  SEED DATA — Executives
-- ─────────────────────────────────────────────

INSERT INTO executives (full_name, role, bio, linkedin_url, photo_url, display_order) VALUES
('Veer Sarin',         'Co-founder',       'Co-founded MVCC to build a direct bridge between McMaster students and the venture ecosystem.', NULL, NULL, 1),
('Diya Shah',          'Co-founder',       'Focused on building early club programming, partnerships, and long-term community growth.', NULL, NULL, 2),
('Benicio Uhart',      'Co-founder',       'Helped shape the club''s founding strategy and student-facing venture programming.', NULL, NULL, 3),
('Josh Michell',       'VP Operations',    'Supports execution across events, logistics, and the club''s internal operating cadence.', NULL, NULL, 4),
('Hannah Lewin',       'VP Marketing',     'Leads brand, outreach, and audience growth across MVCC programs and events.', NULL, NULL, 5),
('Hunaid Rajkotwala',  'Co-VP Education',  'Helps design educational programming around venture capital, startups, and investing.', NULL, NULL, 6),
('Abhay Shenoy',       'Co-VP Education',  'Supports curriculum and learning experiences for analysts and general members.', NULL, NULL, 7);

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

INSERT INTO events (title, event_date, location, description, image_url) VALUES
(
  'Build Canada x MVCC Event with Colin Mang',
  '2024-11-01',
  'McMaster University, Hamilton',
  'McMaster Venture Capital Club partnered with Build Canada for an exclusive event featuring Colin Mang. Members gained insights into the Canadian startup ecosystem, venture trends, and opportunities for student entrepreneurs.',
  NULL  -- TODO: swap in real image
);

-- ─────────────────────────────────────────────
--  SEED DATA — Default Admin
--  Username: admin
--  Replace the placeholder hash before using the admin panel.
-- ─────────────────────────────────────────────

INSERT INTO admins (username, password_hash) VALUES
('admin', 'REPLACE_WITH_BCRYPT_HASH');
