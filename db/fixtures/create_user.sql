INSERT INTO users
  (id, name, email, password_hash, profile_image_url, created_at)
VALUES (
  8000,
  'テストユーザー',
  'sample@example.com',
  '$2y$10$TIwGh45dFdVZ/isQK1a3nuidqnw9LeCmY6k8j7HOPggYJboUQVqpK',
  '/assets/img/default-icon.png',
  NOW()
);
