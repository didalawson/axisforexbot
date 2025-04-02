-- Add balance and active_deposit columns to users table if they don't exist
ALTER TABLE users
ADD COLUMN IF NOT EXISTS balance DECIMAL(20,2) DEFAULT 0.00,
ADD COLUMN IF NOT EXISTS active_deposit DECIMAL(20,2) DEFAULT 0.00;

-- Create transactions table if it doesn't exist
CREATE TABLE IF NOT EXISTS transactions (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    type VARCHAR(50) NOT NULL,
    amount DECIMAL(20,2) NOT NULL,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Add updated_at column to investments table if it doesn't exist
ALTER TABLE investments
ADD COLUMN IF NOT EXISTS updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP;

-- Add rejection_reason column to investments table if it doesn't exist
ALTER TABLE investments
ADD COLUMN IF NOT EXISTS rejection_reason TEXT;

-- Update existing investments to have a default status if none exists
UPDATE investments 
SET status = 'pending' 
WHERE status IS NULL OR status = '';

-- Create index for faster queries
CREATE INDEX IF NOT EXISTS idx_transactions_user_id ON transactions(user_id);
CREATE INDEX IF NOT EXISTS idx_transactions_type ON transactions(type);
CREATE INDEX IF NOT EXISTS idx_transactions_created_at ON transactions(created_at); 