-- Add missing columns to users table if they don't exist

-- Check and add email_verified column
SET @email_verified_exists = (
    SELECT COUNT(*)
    FROM information_schema.COLUMNS 
    WHERE TABLE_SCHEMA = 'axisbot_db' 
    AND TABLE_NAME = 'users'
    AND COLUMN_NAME = 'email_verified'
);

SET @add_email_verified = IF(@email_verified_exists = 0, 
    'ALTER TABLE users ADD COLUMN email_verified BOOLEAN DEFAULT FALSE AFTER role',
    'SELECT "email_verified column already exists"');

PREPARE email_verified_stmt FROM @add_email_verified;
EXECUTE email_verified_stmt;
DEALLOCATE PREPARE email_verified_stmt;

-- Check and add verification_token column
SET @token_exists = (
    SELECT COUNT(*)
    FROM information_schema.COLUMNS 
    WHERE TABLE_SCHEMA = 'axisbot_db' 
    AND TABLE_NAME = 'users'
    AND COLUMN_NAME = 'verification_token'
);

SET @add_token = IF(@token_exists = 0, 
    'ALTER TABLE users ADD COLUMN verification_token VARCHAR(100) NULL AFTER email_verified',
    'SELECT "verification_token column already exists"');

PREPARE token_stmt FROM @add_token;
EXECUTE token_stmt;
DEALLOCATE PREPARE token_stmt;

-- Check and add referral_id column
SET @referral_exists = (
    SELECT COUNT(*)
    FROM information_schema.COLUMNS 
    WHERE TABLE_SCHEMA = 'axisbot_db' 
    AND TABLE_NAME = 'users'
    AND COLUMN_NAME = 'referral_id'
);

SET @add_referral = IF(@referral_exists = 0, 
    'ALTER TABLE users ADD COLUMN referral_id VARCHAR(100) NULL AFTER email_verified, ADD INDEX idx_referral_id (referral_id)',
    'SELECT "referral_id column already exists"');

PREPARE referral_stmt FROM @add_referral;
EXECUTE referral_stmt;
DEALLOCATE PREPARE referral_stmt; 