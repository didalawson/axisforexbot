-- Add my_referral_id column to users table
ALTER TABLE users 
ADD COLUMN my_referral_id VARCHAR(100) NULL AFTER referral_id,
ADD INDEX idx_my_referral_id (my_referral_id);

-- Update description
-- The my_referral_id column stores a unique referral code for each user
-- This is different from referral_id which tracks who referred this user
-- my_referral_id is the code this user uses to refer others 