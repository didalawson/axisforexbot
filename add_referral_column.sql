-- Add referral_id column to users table
ALTER TABLE users 
ADD COLUMN referral_id VARCHAR(100) NULL AFTER role,
ADD INDEX idx_referral_id (referral_id);

-- Add a comment explaining the purpose
-- This column stores the referral ID of the user who referred this account 