-- tags.id を serial から varchar に変更

BEGIN;


ALTER TABLE post_to_tags DROP CONSTRAINT post_to_tags_tag_id_fkey;

ALTER TABLE tags ALTER COLUMN id TYPE VARCHAR(100);

ALTER TABLE post_to_tags ALTER COLUMN tag_id TYPE VARCHAR(100);

ALTER TABLE post_to_tags ADD CONSTRAINT post_to_tags_tag_id_fkey FOREIGN KEY (tag_id) REFERENCES tags (id) ON DELETE SET NULL;


COMMIT;
