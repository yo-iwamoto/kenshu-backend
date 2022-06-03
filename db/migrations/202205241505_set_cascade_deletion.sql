-- post_images.post_id の FK について ON DELETE を CASCADE に変更
ALTER TABLE post_images
DROP CONSTRAINT post_images_post_id_fkey,
ADD CONSTRAINT post_images_post_id_fkey
  FOREIGN KEY (post_id)
  REFERENCES posts (id)
  ON DELETE CASCADE;

-- post_to_tags.post_id の FK について ON DELETE を CASCADE に変更
ALTER TABLE post_to_tags
DROP CONSTRAINT post_to_tags_post_id_fkey,
ADD CONSTRAINT post_to_tags_post_id_fkey
  FOREIGN KEY (post_id)
  REFERENCES posts (id)
  ON DELETE CASCADE;
