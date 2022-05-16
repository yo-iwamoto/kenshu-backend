CREATE TABLE IF NOT EXISTS users (
  id SERIAL PRIMARY KEY,
  email VARCHAR(255) UNIQUE NOT NULL,
  password_hash VARCHAR(100) NOT NULL,
  name VARCHAR(50) NOT NULL,
  profile_image_url VARCHAR(255) NOT NULL,
  created_at TIMESTAMP NOT NULL
);

CREATE TABLE IF NOT EXISTS posts (
  id SERIAL PRIMARY KEY,
  user_id int NOT NULL,
  FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE SET NULL,
  thumbnail_post_image_id int,
  title VARCHAR(100) NOT NULL,
  content TEXT NOT NULL,
  created_at TIMESTAMP NOT NULL
);
CREATE INDEX ON posts (created_at);

CREATE TABLE IF NOT EXISTS post_images (
  id SERIAL PRIMARY KEY,
  post_id int NOT NULL,
  FOREIGN KEY (post_id) REFERENCES posts (id) ON DELETE SET NULL,
  image_url VARCHAR(255) NOT NULL,
  created_at TIMESTAMP NOT NULL
);
ALTER TABLE posts ADD FOREIGN KEY (thumbnail_post_image_id) REFERENCES post_images (id);

CREATE TABLE IF NOT EXISTS tags (
  id SERIAL PRIMARY KEY,
  name VARCHAR(50) NOT NULL,
  created_at TIMESTAMP NOT NULL
);

CREATE TABLE IF NOT EXISTS post_to_tags (
  post_id int NOT NULL,
  FOREIGN KEY (post_id) REFERENCES posts (id) ON DELETE SET NULL,
  tag_id int NOT NULL,
  FOREIGN KEY (tag_id) REFERENCES tags (id) ON DELETE SET NULL,
  created_at TIMESTAMP NOT NULL,
  PRIMARY KEY (post_id, tag_id)
);
CREATE INDEX ON post_to_tags (tag_id);
