-- Add archived_at column to case_inventory
ALTER TABLE case_inventory ADD COLUMN archived_at TIMESTAMP NULL DEFAULT NULL AFTER updated_at;
