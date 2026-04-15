-- Migration: add alt contact name and relationship to case_inventory
ALTER TABLE `case_inventory`
  ADD COLUMN `alt_contact_name` varchar(255) NULL DEFAULT NULL,
  ADD COLUMN `alt_contact_relationship` varchar(100) NULL DEFAULT NULL;

-- Run this in your MySQL/MariaDB instance (phpMyAdmin or mysql CLI):
-- mysql -u root -p your_database_name < 2026-04-13-add-alt-contact-columns.sql
